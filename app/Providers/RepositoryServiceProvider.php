<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Sale;
use App\Repositories\Contracts\ProductRepository;
use App\Repositories\Contracts\SaleRepository;
use App\Repositories\EloquentProductRepository;
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
        $this->app->bind(ProductRepository::class, fn () => new EloquentProductRepository(new Product()));
    }
}
