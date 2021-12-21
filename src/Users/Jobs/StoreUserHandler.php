<?php
declare(strict_types=1);

namespace Todo\Users\Jobs;

use Illuminate\Support\Str;
use Todo\Users\User;
use Todo\Users\UsersRepository;

final class StoreUserHandler
{
    public function __construct(private UsersRepository $usersRepository)
    {
    }

    public function handle(StoreUser $command): void
    {
        $user = $this->usersRepository->find($command->getId());
        if (!$user instanceof User) {
            $user = new User();
            $user->setId(Str::uuid()->toString());
        }

        $user->setEmail($command->getEmail());
        $user->setName($command->getName());

        $this->usersRepository->persist($user);
    }
}
