<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Bus\Dispatcher as DispatcherContract;
use Illuminate\Support\ServiceProvider;
use Todo\Support\Bus\Middleware\DispatchingMiddleware;

final class BusServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->resolving(Dispatcher::class, static function (DispatcherContract $dispatcher) {
            $dispatcher->pipeThrough([
                DispatchingMiddleware::class,
            ]);

            return $dispatcher;
        });
    }
}
