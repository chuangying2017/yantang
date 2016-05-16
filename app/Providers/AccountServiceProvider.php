<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Client\Account\Wallet\WalletRepositoryContract::class,
            \App\Repositories\Client\Account\Wallet\EloquentWalletRepository::class
        );

        $this->app->bind(
            \App\Repositories\Client\Account\Wallet\CreditsRepositoryContract::class,
            \App\Repositories\Client\Account\Wallet\EloquentCreditsRepository::class
        );
    }
}
