<?php
declare(strict_types=1);

namespace Todo\Tasks\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

final class TaskListException extends Exception
{
    public static function notFoundList(string $id): TaskListException
    {
        $message = sprintf('List with ID %s not found', $id);

        return new self($message, 404);
    }
}
