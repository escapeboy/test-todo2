<?php
declare(strict_types=1);

namespace Todo\Tasks\Jobs;

final class DeleteList
{
    public function __construct(private string $listId)
    {
    }

    /**
     * @return string
     */
    public function getListId(): string
    {
        return $this->listId;
    }
}
