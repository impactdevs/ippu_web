<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Mail\MailManager;
use App\Mail\Transport\InfobipTransport;


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
        Schema::defaultStringLength(191);

        $this->app->make(MailManager::class)->extend('infobip', function () {
            $config = config('services.infobip');
            return new InfobipTransport(
                $config['base_url'],
                $config['api_key'],
                $config['email_from']
            );
        });
    }
}
