<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Todo\Tasks\Jobs\CompleteTask;
use Todo\Tasks\Jobs\CompleteTaskHandler;
use Todo\Tasks\Jobs\DeleteList;
use Todo\Tasks\Jobs\DeleteListHandler;
use Todo\Tasks\Jobs\DeleteTask;
use Todo\Tasks\Jobs\DeleteTaskHandler;
use Todo\Tasks\Jobs\StoreListTask;
use Todo\Tasks\Jobs\StoreListTaskHandler;
use Todo\Tasks\Jobs\StoreTaskList;
use Todo\Tasks\Jobs\StoreTaskListHandler;
use Todo\Tasks\Task;
use Todo\Tasks\TaskLists\TaskList;
use Todo\Tasks\TaskLists\TaskListsRepository;

final class ListsController
{
    public function __construct(private TaskListsRepository $taskListsRepository)
    {
    }

    public function list(Request $request): JsonResponse
    {
        $lists = array_map(static function (TaskList $list) {
            return array_merge($list->toArray(), [
                'tasks' => array_map(static fn(Task $task) => $task->toArray(), $list->getTasks()->toArray())
            ]);
        }, $this->taskListsRepository->findBy(['user' => $request->user()]));

        return new JsonResponse($lists);
    }

    public function store(Request $request, ?TaskList $list = null): JsonResponse
    {
        $request->validate([
            'name' => ['required']
        ]);

        $listId = $list !== null ? $list->getId() : Str::uuid()->toString();
        $job = new StoreTaskList($request->get('name'), $request->user('api')->getId(), $listId);
        /** @var TaskList $list */
        $list = app(StoreTaskListHandler::class)->handle($job);

        return new JsonResponse($list->toArray());
    }

    public function delete(TaskList $list): JsonResponse
    {
        $job = new DeleteList($list->getId());
        app(DeleteListHandler::class)->handle($job);

        return new JsonResponse([
            'error' => false,
            'message' => 'List removed'
        ]);
    }

    public function storeTask(Request $request, TaskList $list, ?Task $task = null): JsonResponse
    {
        $request->validate([
            'content' => ['required'],
        ]);

        $job = new StoreListTask(
            $list->getId(),
            $request->get('content'),
            $request->get('priority', 1),
            $task?->getId()
        );
        /** @var Task $task */
        $task = app(StoreListTaskHandler::class)->handle($job);

        return new JsonResponse($task->toArray());
    }

    public function deleteTask(TaskList $list, Task $task): JsonResponse
    {
        $job = new DeleteTask($list->getId(), $task->getId());
        app(DeleteTaskHandler::class)->handle($job);
        return new JsonResponse([
            'error' => false,
            'message' => 'Task removed',
        ]);
    }

    public function completeTask(Task $task): JsonResponse
    {
        $job = new CompleteTask($task->getId());
        app(CompleteTaskHandler::class)->handle($job);

        return new JsonResponse([
            'error' => false,
            'message' => 'Task completed',
        ]);
    }
}
