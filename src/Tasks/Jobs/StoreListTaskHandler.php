<?php
declare(strict_types=1);

namespace Todo\Tasks\Jobs;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Todo\Tasks\Exceptions\TaskListException;
use Todo\Tasks\Task;
use Todo\Tasks\TaskLists\TaskList;
use Todo\Tasks\TaskLists\TaskListsRepository;

final class StoreListTaskHandler
{
    public function __construct(private TaskListsRepository $listsRepository)
    {
    }

    public function handle(StoreListTask $command)
    {
        $list = $this->listsRepository->find($command->getListId());
        if (!$list instanceof TaskList) {
            throw TaskListException::notFoundList($command->getListId());
        }

        $task = Arr::first($list->getTasks(), static function (Task $task) use ($command) {
            return $task->getId() === $command->getId();
        });

        if (!$task instanceof Task) {
            $task = new Task();
            $task->setId(Str::uuid()->toString());
            $list->addTask($task);
        }
        $task->setContent($command->getContent());
        $task->setPriority($command->getPriority());

        $this->listsRepository->persist($list);

        return $task;
    }
}
