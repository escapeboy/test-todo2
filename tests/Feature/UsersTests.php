<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Controllers\Api\AuthController;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tests\Mocks;
use Todo\Support\Doctrine\Validator\InMemoryPresenceVerifier;
use Tests\TestCase;
use Todo\Users\InMemoryUsersRepository;
use Todo\Users\User;
use Todo\Users\UsersRepository;

final class UsersTests extends TestCase
{
    use DatabaseMigrations, Mocks;

    private UsersRepository $repository;

    protected function setUp(): void
    {
        $this->app = $this->createApplication();
        $this->runDatabaseMigrations();
        Artisan::call('passport:install');
        $this->app->singleton(UsersRepository::class, InMemoryUsersRepository::class);
        $this->app->singleton('doctrine.validation.presence', InMemoryPresenceVerifier::class);
        Validator::setPresenceVerifier($this->app->make('doctrine.validation.presence'));
        $this->repository = $this->app->make(UsersRepository::class);
    }

    public function testRegistration(): void
    {
        /** @var AuthController $controller */
        $controller = $this->app->make(AuthController::class);
        $request = (new Request())->merge([
            'email' => 'example@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'name' => 'Test user'
        ]);
        $response = $controller->register($request);
        $jsonResponse = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('Test user', Arr::get($jsonResponse, 'user_data.name'));
        $users = $this->repository->findAll();
        $this->assertCount(1, $users);
        /** @var User $user */
        $user = Arr::first($users);
        $this->assertSame('Test user', $user->getName());
        $this->assertSame('example@example.com', $user->getEmail());
    }

    public function testLogin(): void
    {
        $user = $this->mockUser();
        $user->setPassword(Hash::make('12345678'));

        $this->repository->persist($user);

        /** @var AuthController $controller */
        $controller = $this->app->make(AuthController::class);
        $request = (new Request())->merge([
            'email' => $user->getEmail(),
            'password' => '12345678',
            'remember' => false
        ]);
        $response = $controller->login($request);
        $jsonResponse = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('access_token', $jsonResponse);
        $this->assertSame($user->getName(), Arr::get($jsonResponse, 'user_data.name'));
    }
}
