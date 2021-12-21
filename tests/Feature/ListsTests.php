<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Controllers\Api\ListsController;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Tests\Mocks;
use Tests\TestCase;
use Todo\Tasks\InMemoryTasksRepository;
use Todo\Tasks\TaskLists\InMemoryTaskListsRepository;
use Todo\Tasks\TaskLists\TaskList;
use Todo\Tasks\TaskLists\TaskListsRepository;
use Todo\Tasks\TasksRepository;
use Todo\Users\InMemoryUsersRepository;
use Todo\Users\User;
use Todo\Users\UsersRepository;

final class ListsTests extends TestCase
{
    use DatabaseMigrations, Mocks;

    private User $user;
    private string $accessToken;
    private TaskListsRepository $taskListRepository;
    private TasksRepository $tasksRepository;

    protected function setUp(): void
    {
        $this->app = $this->createApplication();
        $this->runDatabaseMigrations();
        Artisan::call('passport:install');
        $this->app->singleton(UsersRepository::class, InMemoryUsersRepository::class);
        $this->app->singleton(TaskListsRepository::class, InMemoryTaskListsRepository::class);
        $this->app->singleton(TasksRepository::class, InMemoryTasksRepository::class);
        $usersRepository = $this->app->make(UsersRepository::class);
        $this->taskListRepository = $this->app->make(TaskListsRepository::class);
        $this->tasksRepository = $this->app->make(TasksRepository::class);
        $this->user = $this->mockUser();
        $usersRepository->persist($this->user);
        $this->accessToken = $this->loginAndGetToken($usersRepository, $this->user);
    }

    public function testListCreation(): void
    {
        /** @var ListsController $controller */
        $controller = $this->app->make(ListsController::class);
        $list = $this->mockTaskList($this->user);
        $request = $this->createAuthenticatedRequest();
        $request->merge([
            'name' => $list->getName()
        ]);

        $response = $controller->store($request)->getData(true);
        $this->assertSame($list->getName(), Arr::get($response, 'name'));
        $this->assertCount(1, $this->taskListRepository->findAll());
    }

    public function testUpdateList(): void
    {
        $list = $this->mockTaskList($this->user);
        $this->assertCount(0, $this->taskListRepository->findAll());
        $this->taskListRepository->persist($list);
        $newListName = 'Test renamed list';
        $request = $this->createAuthenticatedRequest();
        $request->merge([
            'name' => $newListName
        ]);

        /** @var ListsController $controller */
        $controller = $this->app->make(ListsController::class);

        $response = $controller->store($request, $list)->getData(true);
        $this->assertSame($newListName, Arr::get($response, 'name'));
        $this->assertCount(1, $this->taskListRepository->findAll());
    }

    public function testListsIndex(): void
    {
        $this->taskListRepository->persist($this->mockTaskList($this->user, 'Task 1'));
        $this->taskListRepository->persist($this->mockTaskList($this->user, 'Task 2'));

        $this->assertCount(2, $this->taskListRepository->findAll());

        /** @var ListsController $controller */
        $controller = $this->app->make(ListsController::class);
        $request = $this->createAuthenticatedRequest();
        $response = $controller->list($request)->getData(true);
        $this->assertCount(2, $response);
        $firstList = array_pop($response);
        $this->assertSame('Task 2', Arr::get($firstList, 'name'));
        $secondList = array_pop($response);
        $this->assertSame('Task 1', Arr::get($secondList, 'name'));
    }

    public function testAddTaskToList(): void
    {
        $list = $this->mockTaskList($this->user);
        $this->taskListRepository->persist($list);
        /** @var ListsController $controller */
        $controller = $this->app->make(ListsController::class);
        $request = $this->createAuthenticatedRequest();
        $request->merge([
            'content' => 'Test task',
            'priority' => 1
        ]);

        $response = $controller->storeTask($request, $list)->getData(true);
        $this->assertSame('Test task', Arr::get($response, 'content'));
        $this->assertSame(1, Arr::get($response, 'priority'));
        /** @var TaskList $list */
        $list = $this->taskListRepository->find($list->getId());
        $this->assertCount(1, $list->getTasks());
        $this->assertSame('Test task', $list->getTasks()->first()->getContent());
        $this->assertSame(1, $list->getTasks()->first()->getPriority());
    }

    public function testUpdateTask(): void
    {
        $list = $this->mockTaskList($this->user);
        $task = $this->mockTask();
        $list->addTask($task);
        $this->taskListRepository->persist($list);
        /** @var ListsController $controller */
        $controller = $this->app->make(ListsController::class);
        $request = $this->createAuthenticatedRequest();
        $request->merge([
            'content' => 'Test updated content'
        ]);
        $response = $controller->storeTask($request, $list, $task)->getData(true);
        $this->assertSame('Test updated content', Arr::get($response, 'content'));
        $this->assertSame('Test updated content', $list->getTasks()->first()->getContent());
    }

    public function testListDelete(): void
    {
        $list = $this->mockTaskList($this->user);
        $this->taskListRepository->persist($list);
        /** @var ListsController $controller */
        $controller = $this->app->make(ListsController::class);
        $this->assertCount(1, $this->taskListRepository->findAll());

        $response = $controller->delete($list)->getData(true);
        $this->assertFalse(Arr::get($response, 'error'));
        $this->assertCount(0, $this->taskListRepository->findAll());
    }

    public function testRemoveTask(): void
    {
        $list = $this->mockTaskList($this->user);
        $task = $this->mockTask();
        $list->addTask($task);
        $this->taskListRepository->persist($list);
        /** @var ListsController $controller */
        $controller = $this->app->make(ListsController::class);

        $response = $controller->deleteTask($list, $task)->getData(true);
        $this->assertFalse(Arr::get($response, 'error'));
        $this->assertCount(0, $list->getTasks());
    }

    public function testTaskCompletion(): void
    {
        $task = $this->mockTask();
        $this->tasksRepository->persist($task);

        /** @var ListsController $controller */
        $controller = $this->app->make(ListsController::class);
        $this->assertNull($task->getCompletedAt());
        $response = $controller->completeTask($task)->getData(true);
        $this->assertFalse(Arr::get($response, 'error'));
        $this->assertNotNull($task->getCompletedAt());
    }
}
