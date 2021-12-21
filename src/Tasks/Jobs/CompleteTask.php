<?php
declare(strict_types=1);

namespace Todo\Tasks\Jobs;

final class CompleteTask
{
    public function __construct(private string $taskId)
    {
    }

    /**
     * @return string
     */
    public function getTaskId(): string
    {
        return $this->taskId;
    }
}
