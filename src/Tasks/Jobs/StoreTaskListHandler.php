<?php
declare(strict_types=1);

namespace Todo\Tasks\Jobs;

use Illuminate\Support\Str;
use Todo\Tasks\TaskLists\TaskList;
use Todo\Tasks\TaskLists\TaskListsRepository;
use Todo\Users\UsersRepository;

final class StoreTaskListHandler
{
    public function __construct(
        private TaskListsRepository $taskListsRepository,
        private UsersRepository $usersRepository
    ) {
    }

    public function handle(StoreTaskList $command)
    {
        $list = $this->taskListsRepository->find($command->getId());

        if (!$list instanceof TaskList) {
            $list = new TaskList();
            $list->setId(Str::uuid()->toString());
        }
        $list->setName($command->getName());
        $user = $this->usersRepository->find($command->getUserId());
        $list->setUser($user);

        $this->taskListsRepository->persist($list);

        return $list;
    }
}
