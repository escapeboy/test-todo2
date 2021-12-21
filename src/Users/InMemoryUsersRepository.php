<?php
declare(strict_types=1);

namespace Todo\Users;

use Todo\Support\Doctrine\BaseInMemoryEntityRepository;

final class InMemoryUsersRepository extends BaseInMemoryEntityRepository implements UsersRepository
{

    protected function getEntityClass(): string
    {
        return User::class;
    }
}
