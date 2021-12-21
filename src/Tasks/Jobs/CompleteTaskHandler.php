<?php
declare(strict_types=1);

namespace Todo\Tasks\Jobs;

use Todo\Tasks\Exceptions\TaskException;
use Todo\Tasks\Task;
use Todo\Tasks\TasksRepository;

final class CompleteTaskHandler
{
    public function __construct(private TasksRepository $tasksRepository)
    {
    }

    public function handle(CompleteTask $command): void
    {
        $task = $this->tasksRepository->find($command->getTaskId());
        if (!$task instanceof Task) {
            throw TaskException::taskNotFound($command->getTaskId());
        }

        $task->setCompletedAt(new \DateTime());

        $this->tasksRepository->persist($task);
    }
}
