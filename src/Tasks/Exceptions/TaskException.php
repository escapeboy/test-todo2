<?php
declare(strict_types=1);

namespace Todo\Tasks\Exceptions;

use Exception;

final class TaskException extends Exception
{
    public static function taskNotFound(string $id): TaskException
    {
        $message = sprintf('Task with id %s not found', $id);

        return new self($message, 404);
    }
}
