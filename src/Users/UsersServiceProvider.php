<?php
declare(strict_types=1);

namespace Todo\Users;

use Illuminate\Support\ServiceProvider;

final class UsersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UsersRepository::class, static function ($app) {
            return new DoctrineUsersRepositoryDoctrine(
                $app['em'],
                $app['em']->getClassMetadata(User::class)
            );
        });
    }
}
