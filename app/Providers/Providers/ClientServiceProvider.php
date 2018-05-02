<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ClientServiceProvider extends ServiceProvider {

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
            \App\Repositories\Client\Account\Credits\CreditsRepositoryContract::class,
            \App\Repositories\Client\Account\Credits\EloquentCreditsRepository::class
        );

        $this->app->bind(
            \App\Repositories\Client\ClientRepositoryContract::class,
            \App\Repositories\Client\EloquentClientRepository::class
        );


    }

}
