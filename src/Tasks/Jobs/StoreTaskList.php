<?php
declare(strict_types=1);

namespace Todo\Tasks\Jobs;

final class StoreTaskList
{
    public function __construct(
        private string $name,
        private string $userId,
        private ?string $id = null,
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
}
