<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\View\Composers\AdminDashboardComposer;
use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Darryldecode\Cart\Facades\CartFacade as Cart;

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
        Carbon::setLocale('id');

        // Cart count composer untuk semua view
        View::composer('*', function ($view) {
            $cartCount = 0;
            try {
                $cartCount = Cart::getTotalQuantity();
            } catch (\Throwable $e) {
                $cartCount = 0;
            }

            $view->with('cartCount', $cartCount);
        });

        // Admin dashboard composer untuk semua view yang menggunakan layout admin
        View::composer(['layouts.admin', 'admin.*'], AdminDashboardComposer::class);

        // Share categories data ke semua view (dengan nama berbeda agar tidak konflik)
        View::composer('*', function ($view) {
            $allCategories = collect();
            try {
                if (Schema::hasTable('categories')) {
                    $allCategories = Category::orderBy('name')->get();
                }
            } catch (\Throwable $e) {
                $allCategories = collect();
            }
            $view->with('allCategories', $allCategories);
        });

        Paginator::useBootstrap();
    }
}
