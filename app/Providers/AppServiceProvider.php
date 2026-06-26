<?php

namespace App\Providers;

use App\Models\CustomerPayment;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.sidebar', function ($view) {
            $chequeClearanceCount = CustomerPayment::whereIn('payment_mode', ['cheque', 'dd'])
                ->where('payment_status', 'hold')
                ->where(function ($query) {
                    $query->whereNull('cheque_status')
                        ->orWhere('cheque_status', '!=', 'cleared');
                })
                ->count();

            $view->with('chequeClearanceCount', $chequeClearanceCount);
        });
    }
}
