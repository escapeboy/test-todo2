<?php
declare(strict_types=1);

namespace Todo\Tasks\TaskLists;

use Todo\Support\Doctrine\BaseInMemoryEntityRepository;

final class InMemoryTaskListsRepository extends BaseInMemoryEntityRepository implements TaskListsRepository
{

    protected function getEntityClass(): string
    {
        return TaskList::class;
    }
}
