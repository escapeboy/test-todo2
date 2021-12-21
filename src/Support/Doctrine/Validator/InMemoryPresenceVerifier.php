<?php
declare(strict_types=1);

namespace Todo\Support\Doctrine\Validator;

use Illuminate\Validation\PresenceVerifierInterface;
use Todo\Users\InMemoryUsersRepository;
use Todo\Users\User;
use Todo\Support\Doctrine\BaseInMemoryEntityRepository;

final class InMemoryPresenceVerifier implements PresenceVerifierInterface
{
    public function getCount($collection, $column, $value, $excludeId = null, $idColumn = null, array $extra = []): int
    {
        /** @var BaseInMemoryEntityRepository $repository */
        $repository = match ($collection) {
            User::class => app(InMemoryUsersRepository::class)
        };
        $items = $repository->findBy([$column, $value]);

        return count($items);
    }

    public function getMultiCount($collection, $column, array $values, array $extra = []): void
    {
        // TODO: Implement getMultiCount() method.
    }
}
