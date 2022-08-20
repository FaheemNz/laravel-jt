<?php

namespace App\Http\Controllers\Api;

use App\ChatRoom;
use App\CounterOffer;
use App\Exceptions\PaymentException;
use App\Http\Controllers\BaseController;
use App\Lib\Helper;
use App\Offer;
use App\Order;
use App\Payment;
use App\Services\Interfaces\PaymentServiceInterface;
use App\Transaction;
use App\Utills\Constants\OfferStatus;
use App\Utills\Constants\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @group Payment
 */
class PaymentController extends BaseController
{
    protected $PaymentService;
    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->PaymentService = $paymentService;
    }

    /**
     * Process Payment of the Order
     *
     * @bodyParam order_id integer required ID of the Order
     * @bodyParam type string required Type of the Payment (Credit Card, Debit Card, Bank)
     *
     * @response {
     *  "success": true,
     *  "message": "Order Payment has been completed successfully",
     *  "data"   : []
     * }
     *
     * @response 400 {
     *  "success": false,
     *  "message": "Some error occured while processing payment.",
     *  "data"   : []
     * }
     */
    public function store(Request $request, Payment $payment)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'order_id'              =>      'required|integer',
                'offer_id'              =>      'required|integer'
            ], [
                'order_id.required'     =>      'Order.ID is required',
                'order_id.integer'      =>      'Order.ID is not correct',
                'offer_id.required'     =>      'Offer.ID is required',
                'offer_id.integer'      =>      'Offer.ID is not in correct format'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Payment Error', $validator->errors()->all(), 422);
            }

            Helper::log('### Payment - Offer Error ###', [
                'order_id'  => $request->order_id,
                'offer_id'  => $request->offer_id,
                'user_id'   => auth()->id()
            ]);

            $order = Order::findOrFail($request->order_id);
            $duplicatePaymentCheck = Payment::where('order_id', $request->order_id)
                ->where('user_id', auth()->id())
                ->first();

            if (!empty($duplicatePaymentCheck)) {
                Helper::log('### Payment - Duplicate ###', 'A payment already exists for this Order');

                return $this->sendError('Payment Error', ['A Payment already exists for this Order']);
            }

            $orderOffer = Offer::where('id', $request->offer_id)
                ->where('order_id', $request->order_id)
                ->first();

            if(!$orderOffer){
                return $this->sendError('Payment Error', ['The offer you provided does was not found for the Order']);
            }


            if($orderOffer->status == OfferStatus::OPEN){
                $orderOffer->update(["status" => OfferStatus::PAYMENT_IN_PROGRESS]);
            } else if($orderOffer->counterOffer && $orderOffer->counterOffer->status == OfferStatus::ACCEPTED){
                $orderOffer->counterOffer(["status" => OfferStatus::PAYMENT_IN_PROGRESS]);
            }else{
                return $this->sendError('Payment Error', ['Neither offer nor counter offer accepted yet.']);
            }

            $order->update([
                "status"        => OrderStatus::PAYMENT_IN_PROGRESS,
                "traveler_id"   => $orderOffer->user_id
            ]);

            $payment->saveOrderPayment($order);

            DB::commit();

            return $this->sendResponse(
                ['Order Payment has been completed successfully'],
                'Payment Success',
                200
            );
        } catch (PaymentException $paymentException) {
            Helper::log('### Payment - Exception ###', Helper::getExceptionInfo($paymentException));
            Helper::log('### Payment - Exception ###', 'Rolling Back...');

            DB::rollBack();

            return $this->sendError(
                'Payment Exception',
                ['Order Payment was not successful ' . $paymentException->getMessage()]
            );
        } catch (\Exception $exception) {
            Helper::log('### Payment - Exception ###', Helper::getExceptionInfo($exception));
            Helper::log('### Payment - Exception ###', 'Rolling Back...');

            DB::rollBack();

            return $this->sendError(
                'Payment Exception',
                ['Order Payment was not successful ' . $exception->getMessage()]
            );
        }
    }

    public function cleared(Order $order)
    {
        $status = $this->PaymentService->clearPayment($order);
        if(!$status){
            return $this->sendError('Payment Exception', ['You can\'t paid this order']);
        }
        return $this->sendResponse(
            ['Order Paid Successfully'],
            'Payment Paid Success',
            200
        );
    }


    public function generateLink(Order $order)
    {
        $order  = "order=".$order->id;
        $user   = "user=".auth()->id();
        $time   = "time=".now()->format('y-m-d H:i:s');
        $str    = bin2hex($order.','.$user.','.$time);

        return $this->sendResponse(['url' => route('nift-pay', $str)],"Payment Link Generated Successfully");
    }
}
