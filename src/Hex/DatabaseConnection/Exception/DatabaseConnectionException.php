<?php

declare(strict_types=1);

namespace Hex\DatabaseConnection;

use PDOException;

class DatabaseConnectionException extends PDOException
{
    /**
     * Override constructor to avoid PHP BUGS #51742, #39615
     *
     * @param string  $message
     * @param integer $code
     */
    public function __construct(string $message, int $code)
    {
        $this->message = $message;
        $this->code    = $code;
    }
}
