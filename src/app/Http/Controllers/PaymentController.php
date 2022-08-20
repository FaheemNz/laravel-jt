<?php

namespace App\Http\Controllers;

use App\ChatRoom;
use App\Currency;
use App\Http\Resources\CustomerOrderResource;
use App\Lib\Helper;
use App\Notifications\PaymentClearedNotification;
use App\Order;
use App\Payment;
use App\Services\Interfaces\PayableServiceInterface;
use App\Services\Interfaces\PaymentServiceInterface;
use App\Transaction;
use App\Utills\Constants\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $PayableService;
    protected $PaymentService;
    public function __construct(PayableServiceInterface $payableService, PaymentServiceInterface $paymentService)
    {
        $this->PayableService = $payableService;
        $this->PaymentService = $paymentService;
    }

    public function index()
    {
        $orders = Order::where("status","<>", OrderStatus::NEW)
            ->where(function($query){
                $query->where('is_traveler_paid', 0)
                ->orWhere('is_customer_paid', 0);
            })->get();

        $orders = CustomerOrderResource::collection($orders)->response()->getData(true)['data'];
        return view('payments.index', compact('orders'));
    }

    public function clearPayment(Order $order){
        $this->PaymentService->clearPayment($order);
        return redirect()->route('payments.index');
    }

    public function clearCustomerPayment(Order $order)
    {
        if (auth()->user()->isAdmin){
            DB::beginTransaction();
            try {
                $order_Data = [
                    'is_customer_paid' => true,
                    'status'           => $order->is_traveler_paid? OrderStatus::COMPLETED : $order->status
                ];

                $payable  = $this->PayableService->generatePayable($order->payment);

                $currency = Currency::where('short_code', 'PKR')->first();

                $total    = $payable['customer']['total_returnable'];
                Transaction::create([
                    "amount"                => $total,
                    "pkr_amount"            => Helper::convertCurrency($order->currency_id, $currency->id, $total),
                    "currency_id"           => $currency->id,
                    "source"                => "platform",
                    "transaction_details"   => "Customer Payment Has Been Released against Order# ".$order->id." (" . $order->name . " )",
                    "status"                => "settled",
                    "ref_no"                => Str::uuid(),
                    "user_id"               => auth()->id(),
                    "order_id"              => $order->id,
                ]);

                $order->update($order_Data);
                // Send Customer Notification
                $order->user->notify(new PaymentClearedNotification($order, "Your Total Returnables", $total));

                DB::commit();
            }catch (\Exception $exception) {
                Helper::log('### Customer Payment - Clear ###', Helper::getExceptionInfo($exception));
                DB::rollBack();
                return 'Some error occurred while clearing customer payment. Please try later';
            }
        }
        return redirect()->route('payments.index');
    }


    public function clearTravellerPayment(Order $order)
    {
        if (auth()->user()->isAdmin){
            DB::beginTransaction();
            try {
                $order_Data = [
                    'is_traveler_paid' => true,
                    'status'           => $order->is_customer_paid? OrderStatus::COMPLETED : $order->status
                ];

                $payable = $this->PayableService->generatePayable($order->payment);
                $currency = Currency::where('short_code', 'PKR')->first();
                $total = $payable['traveller']['total_receivable'];
                Transaction::create([
                    "amount"                => $total,
                    "pkr_amount"            => Helper::convertCurrency($order->currency_id, $currency->id, $total),
                    "currency_id"           => 1,
                    "source"                => "platform",
                    "transaction_details"   => "Traveller Payment Has Been Released against Order# ".$order->id." (" . $order->name . " )",
                    "status"                => "settled",
                    "ref_no"                => Str::uuid(),
                    "user_id"               => auth()->id(),
                    "order_id"              => $order->id,
                ]);

                $order->update($order_Data);

                // Send Traveller Notification
                $order->traveller->notify(new PaymentClearedNotification($order,"Your Total Receivables", $total));

                DB::commit();
            }catch (\Exception $exception) {
                Helper::log('### Traveller Payment - Clear ###', Helper::getExceptionInfo($exception));
                DB::rollBack();
                return 'Some error occurred while clearing traveller payment. Please try later';
            }
        }
        return redirect()->route('payments.index');
    }
}
