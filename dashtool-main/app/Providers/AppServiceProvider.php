<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

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
        Paginator::useBootstrap();

        Builder::macro('whereLike', function ($field, $value) {
            return $this->where(DB::raw(" lower($field) "), ' like ', '%'.strtolower($value).'%');
        });

        Builder::macro('orWhereLike', function ($field, $value) {
            return $this->orWhere(DB::raw(" lower($field) "), ' like ', '%'.strtolower($value).'%');
        });
    }
}
