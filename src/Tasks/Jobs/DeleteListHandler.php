<?php
declare(strict_types=1);

namespace Todo\Tasks\Jobs;

use Todo\Tasks\TaskLists\TaskList;
use Todo\Tasks\TaskLists\TaskListsRepository;

final class DeleteListHandler
{
    public function __construct(private TaskListsRepository $taskListsRepository)
    {
    }

    public function handle(DeleteList $command): void
    {
        $list = $this->taskListsRepository->find($command->getListId());
        if (!$list instanceof TaskList) {
            return;
        }
        $this->taskListsRepository->remove($list);
    }
}
