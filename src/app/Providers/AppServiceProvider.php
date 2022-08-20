<?php

namespace App\Providers;

use App\Services\ImageService;
use App\Services\Interfaces\ImageServiceInterface;
use App\Services\Interfaces\PayableServiceInterface;
use App\Services\Interfaces\PaymentServiceInterface;
use App\Services\PayableService;
use App\Services\PaymentService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Observers\OrderObserver;
use App\Order;
use App\Observers\MessageObserver;
use App\Message;
use App\Observers\OfferObserver;
use App\Offer;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ImageServiceInterface::class, ImageService::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
        $this->app->bind(PayableServiceInterface::class, PayableService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);
        Order::observe(OrderObserver::class);
        Message::observe(MessageObserver::class);
        Offer::observe(OfferObserver::class);

        view()->composer(['partials.sidebar-nav'], function($view)
        {
            $notifications = auth()->user()->unreadnotifications;
            $view->with([
                'notifications'=> $notifications
            ]);
        });
    }
}
