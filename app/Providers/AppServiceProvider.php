<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Console\Migrations\RefreshCommand;
use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\Console\WipeCommand;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

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
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        RateLimiter::for('otp', function (Request $request) {
            return Limit::perMinute(2)->by($request->ip());
        });

        RateLimiter::for('alert-admin', function (Request $request) {
            return Limit::perMinute(5)
                ->by(optional($request->user())->id ?: $request->ip())
                ->response(function () {
                    throw new TooManyRequestsHttpException(60, 'Too many requests. Please try again later.');
                });
        });

        WipeCommand::prohibit($this->app->isProduction());
        FreshCommand::prohibit($this->app->isProduction());
        ResetCommand::prohibit($this->app->isProduction());
        RefreshCommand::prohibit($this->app->isProduction());
        RollbackCommand::prohibit($this->app->isProduction());
    }
}
