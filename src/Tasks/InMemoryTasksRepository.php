<?php
declare(strict_types=1);

namespace Todo\Tasks;

use Todo\Support\Doctrine\BaseInMemoryEntityRepository;

final class InMemoryTasksRepository extends BaseInMemoryEntityRepository implements TasksRepository
{
    protected function getEntityClass(): string
    {
        return Task::class;
    }
}
