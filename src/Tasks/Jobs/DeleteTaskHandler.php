<?php
declare(strict_types=1);

namespace Todo\Tasks\Jobs;

use Todo\Tasks\Exceptions\TaskListException;
use Todo\Tasks\TaskLists\TaskList;
use Todo\Tasks\TaskLists\TaskListsRepository;

final class DeleteTaskHandler
{
    public function __construct(private TaskListsRepository $taskListsRepository)
    {
    }

    public function handle(DeleteTask $command): void
    {
        $list = $this->taskListsRepository->find($command->getListId());
        if (!$list instanceof TaskList) {
            throw TaskListException::notFoundList($command->getListId());
        }

        foreach ($list->getTasks() as $key => $task) {
            if ($task->getId() !== $command->getTaskId()) {
                continue;
            }

            $list->getTasks()->remove($key);
        }

        $this->taskListsRepository->persist($list);
    }
}
