<?php

namespace App\Providers;

use App\Http\Repository\CardRepositoryInterface;
use App\Http\Repository\ColumnRepositoryInterface;
use App\Http\Repository\Eloquent\CardRepository;
use App\Http\Repository\Eloquent\ColumnRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ColumnRepositoryInterface::class, ColumnRepository::class);
        $this->app->bind(CardRepositoryInterface::class, CardRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
