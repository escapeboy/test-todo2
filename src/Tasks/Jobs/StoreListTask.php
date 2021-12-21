<?php
declare(strict_types=1);

namespace Todo\Tasks\Jobs;

final class StoreListTask
{
    public function __construct(
        private string $listId,
        private string $content,
        private int $priority = 1,
        private ?string $id = null
    ) {
    }

    /**
     * @return string
     */
    public function getListId(): string
    {
        return $this->listId;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
}
