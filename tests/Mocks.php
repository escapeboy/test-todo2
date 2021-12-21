<?php
declare(strict_types=1);

namespace Tests;

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Todo\Tasks\Task;
use Todo\Tasks\TaskLists\TaskList;
use Todo\Users\User;
use Todo\Users\UsersRepository;

trait Mocks
{
    private function mockUser(string $email = null, string $name = null): User
    {
        $user = new User();
        $user->setId(Str::uuid()->toString());
        $user->setName($name ?: 'Test user');
        $user->setEmail($email ?: 'test@example.com');
        $user->setPassword(Hash::make('12345678'));

        return $user;
    }

    private function loginAndGetToken(UsersRepository $usersRepository, User $user = null): string
    {
        $email = 'test@example.com';
        if (!$user) {
            $user = $this->mockUser($email);
            $user->setPassword(Hash::make('12345678'));
            $usersRepository->persist($user);
        }

        $controller = $this->app->make(AuthController::class);
        $request = (new Request())->merge([
            'email' => $user->getEmail(),
            'password' => '12345678',
            'remember' => false,
            'scopes' => ['*']
        ]);
        $response = $controller->login($request);
        $jsonResponse = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        return Arr::get($jsonResponse, 'access_token');
    }

    private function mockTaskList(User $user, string $name = 'Task list name'): TaskList
    {
        $list = new TaskList();
        $list->setId(Str::uuid()->toString());
        $list->setName($name);
        $list->setUser($user);

        return $list;
    }

    private function mockTask(string $content = 'Test content', int $priority = 1)
    {
        $task = new Task();
        $task->setId(Str::uuid()->toString());
        $task->setContent($content);
        $task->setPriority($priority);

        return $task;
    }

    private function createAuthenticatedRequest(): Request
    {
        $request = new Request();
        $this->authenticateRequest($request);

        return $request;
    }

    private function authenticateRequest(Request $request): void
    {
        $request->headers->set('Authentication', 'Bearer ' . $this->accessToken);
        $request->setUserResolver(function () {
            return $this->user;
        });
    }
}
