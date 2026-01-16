<?php

namespace App\Providers;

use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\MessageRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * interface ve class eşleşmesini bilmesi otomatik inject edilmesi için eklendi
     */
    public function register(): void
    {
        $this->app->bind(
            MessageRepositoryInterface::class,
            MessageRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
