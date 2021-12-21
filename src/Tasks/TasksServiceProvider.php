<?php
declare(strict_types=1);

namespace Todo\Tasks;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Todo\Tasks\TaskLists\TaskListsRepository;

final class TasksServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TasksRepository::class, static function ($app) {
            return new DoctrineTasksRepository(
                $app['em'],
                $app['em']->getClassMetadata(Task::class)
            );
        });

        Route::bind('task', function ($value) {
            return app(TasksRepository::class)->find($value);
        });

        Route::bind('list', function ($value) {
            return app(TaskListsRepository::class)->find($value);
        });
    }
}
