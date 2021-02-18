<?php

declare(strict_types=1);

namespace Hex\DatabaseConnection;

use PDO;

interface DatabaseConnectionInterface
{
    /**
     * Create a new connection
     *
     * @return PDO
     */
    public function open(): PDO;

    /**
     * close the current connection
     */
    public function close(): void;
}
