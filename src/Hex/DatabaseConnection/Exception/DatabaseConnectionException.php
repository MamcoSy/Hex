<?php

declare(strict_types=1);

namespace Hex\DatabaseConnection;

use PDOException;

class DatabaseConnectionException extends PDOException
{
    public function __construct(string $message, int $code)
    {
        $this->message = $message;
        $this->code    = $code;
    }
}
