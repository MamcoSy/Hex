<?php

declare(strict_types=1);

namespace Hex\LiquidOrm\DataMapper;

use Hex\LiquidOrm\DataMapper\Exception\DataMapperInvalidArgumentException;

class DataMapperEnvConfig
{
    protected array $credentials = [];

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Get database credentials for the given driver key
     *
     * @param string $driverKey
     *
     * @return array
     */
    public function getDatabaseCredentials(string $driverKey): array
    {
        foreach ($this->credentials as $driver)
        {
            if (array_key_exists($driverKey, $driver))
            {
                return $driver[$driverKey];
            }
        }

        return [];
    }

    /**
     * Checking if  the given credentials are valid
     *
     * @param array $credentials
     *
     * @return boolean
     */
    private function isCredentialsValid(array $credentials)
    {
        if (empty($credentials))
        {
            throw new DataMapperInvalidArgumentException('Invalid credentials. Credentials can not be empty');
        }
    }
}
