<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider

{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /*
        protected $policies = [
            \App\Models\Item::class => \App\Policies\ItemPolicy::class,
            \App\Models\Auction::class => \App\Policies\AuctionPolicy::class,
            \App\Models\Catalogue::class => \App\Policies\CataloguePolicy::class,
            // ... other mappings
        ];*/

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
