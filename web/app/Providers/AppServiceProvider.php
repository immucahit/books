<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\BookService\BookService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BookService::class,function(){
            return new BookService(env('BOOKS_API'));
        });
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
