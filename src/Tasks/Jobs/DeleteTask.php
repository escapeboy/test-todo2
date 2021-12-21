<?php
declare(strict_types=1);

namespace Todo\Tasks\Jobs;

final class DeleteTask
{
    public function __construct(
        private string $listId,
        private string $taskId
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
    public function getTaskId(): string
    {
        return $this->taskId;
    }
}
