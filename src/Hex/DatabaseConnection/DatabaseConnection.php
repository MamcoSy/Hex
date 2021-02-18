<?php

declare(strict_types=1);

namespace Hex\DatabaseConnection;

use PDO;
use PDOException;

class DatabaseConnection implements DatabaseConnectionInterface
{
    protected ?PDO $pdoInstance;

    // Database credentials
    protected array $credentials;

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
        $this->pdoInstance = null;
    }

    /**
     * @inheritDoc
     */
    public function open(): PDO
    {
        if ($this->pdoInstance)
        {
            try
            {
                $this->pdoInstance = new PDO(
                    $this->credentials['dsn'],
                    $this->credentials['username'],
                    $this->credentials['password'],
                    [
                        PDO::ATTR_EMULATE_PREPARES   => false,
                        PDO::ATTR_PERSISTENT         => true,
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            }
            catch (PDOException $e)
            {
                throw new DatabaseConnectionException($e->getMessage(), (int)$e->getCode());
            }

            return $this->pdoInstance;
        }
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        $this->pdoInstance = null;
    }
}
