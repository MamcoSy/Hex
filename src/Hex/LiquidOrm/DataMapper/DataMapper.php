<?php

declare(strict_types=1);

namespace Hex\LiquidOrm\DataMapper;

use Hex\DatabaseConnection\DatabaseConnectionInterface;
use Hex\LiquidOrm\DataMapper\Exception\DataMapperException;
use PDO;
use PDOStatement;

class DataMapper implements DataMapperInterface
{
    protected DatabaseConnectionInterface $databaseConnection;

    protected PDOStatement $pdoStatement;

    public function __construct(DatabaseConnectionInterface $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    private function isEmpty($value, string $errorMessage)
    {
        if (!empty($value))
        {
            throw new DataMapperException($errorMessage);
        }
    }

    private function isArray($value)
    {
        if (!is_array($value))
        {
            throw new DataMapperException('Your argument need to be an array');
        }
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $sqlQuery): self
    {
        $this->pdoStatement = $this->databaseConnection->open()->prepare($sqlQuery);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDataType($value)
    {
        try
        {
            switch ($value) {
                case is_bool($value):
                case intval($value):
                    $dataType = PDO::PARAM_INT;
                    break;

                case is_null($value):
                    $dataType = PDO::PARAM_NULL;
                    break;

                default:
                    $dataType = PDO::PARAM_STR;
                    break;
            }

            return $dataType;
        }
        catch (\Throwable $th)
        {
            throw new DataMapperException();
        }
    }

    /**
     * Binding the given values
     *
     * @param array $values
     *
     * @throws DataMapperException
     *
     * @return PDOStatement
     */
    protected function bindValues(array $values): PDOStatement
    {
        $this->isArray($values);

        foreach ($values as $key => $value)
        {
            $this->pdoStatement->bindValue(':' . $key, $value, $this->getDataType($value));
        }

        return $this->pdoStatement;
    }

    /**
     * Binding the given search values
     *
     * @param array $values
     *
     * @throws DataMapperException
     *
     * @return PDOStatement
     */
    protected function bindSearchValues(array $values): PDOStatement
    {
        $this->isArray($values);

        foreach ($values as $key => $value)
        {
            $this->pdoStatement->bindValue(':' . $key, '%' . $value . '%', $this->getDataType($value));
        }

        return $this->pdoStatement;
    }

    /**
     * @inheritDoc
     */
    public function bindParameters(array $parameters, bool $isSearch = false): self
    {
        $this->isArray($parameters);
        $isSearch ? $this->bindSearchValues($parameters) : $this->bindValues($parameters);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($this->pdoStatement)
        {
            return $this->pdoStatement->execute();
        }
    }

    /**
     * @inheritDoc
     */
    public function numRows(): int
    {
        if ($this->pdoStatement)
        {
            return $this->pdoStatement->rowCount();
        }
    }

    /**
     * @inheritDoc
     */
    public function fetch(): object
    {
        if ($this->pdoStatement)
        {
            return $this->pdoStatement->fetch(PDO::FETCH_OBJ);
        }
    }

    /**
     * @inheritDoc
     */
    public function fetchAll(): array
    {
        if ($this->pdoStatement)
        {
            return $this->pdoStatement->fetchAll();
        }
    }

    /**
     * @inheritDoc
     */
    public function getLastId(): int
    {
        try
        {
            if ($this->databaseConnection)
            {
                $lastInsertId = $this->databaseConnection->open()->lastInsertId();
                if (!empty($lastInsertId))
                {
                    return intval($lastInsertId);
                }
            }
        }
        catch (\Throwable $th)
        {
            throw $th;
        }
    }
}
