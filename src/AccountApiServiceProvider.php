<?php

namespace ReciclareAccount\AccountClientSDK;

use Illuminate\Support\ServiceProvider as PackageServiceProvider;

class AccountApiServiceProvider extends PackageServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void 
    {
        $this->mergeConfigFrom(
        __DIR__.'/../config/account_client.php', 'account_client'
    );
    }

    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/account_client.php' => config_path('account_client.php'),
        ]);
    }
}
