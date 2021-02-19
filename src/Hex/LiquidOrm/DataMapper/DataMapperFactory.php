<?php

declare(strict_types=1);

namespace Hex\LiquidOrm\DataMapper;

use Hex\DatabaseConnection\DatabaseConnectionInterface;
use Hex\LiquidOrm\DataMapper\Exception\DataMapperException;

class DataMapperFactory
{
    public function __construct()
    {
    }

    /**
     * Return a data mapper
     *
     * @param string $databaseConnectionString
     * @param string $DataMapperEnvConfigString
     *
     * @return DataMapperInterface
     */
    public function create(string $databaseConnectionString, string $DataMapperEnvConfigString): DataMapperInterface
    {
        $credentials              = (new $DataMapperEnvConfigString([]))->getDatabaseCredentials('mysql');
        $databaseConnectionObject = new $databaseConnectionString($credentials);

        if (!$databaseConnectionObject instanceof DatabaseConnectionInterface)
        {
            throw new DataMapperException("{$databaseConnectionString} doesn't implement DatabaseConnectionInterface");
        }

        return new DataMapper($databaseConnectionObject);
    }
}
