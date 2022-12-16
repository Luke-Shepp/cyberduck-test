<?php

namespace App\Providers;

use App\Models\Sale;
use App\Repositories\Contracts\SaleRepository;
use App\Repositories\EloquentSaleRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SaleRepository::class, fn () => new EloquentSaleRepository(new Sale()));
    }
}
