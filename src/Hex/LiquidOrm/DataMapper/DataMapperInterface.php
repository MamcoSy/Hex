<?php

declare(strict_types=1);

namespace Hex\LiquidOrm\DataMapper;

use Hex\LiquidOrm\DataMapper\Exception\DataMapperException;

interface DataMapperInterface
{
    /**
     * Prepare the sql query
     *
     * @param string $sqlQuery
     *
     * @return self
     */
    public function prepare(string $sqlQuery): self;

    /**
     * Return the appropriate PDO data type for the given value
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function getDataType($value);

    /**
     * Bind parameters with there corresponding data type
     *
     * Note: this is a combination method that combine two methods.
     * One of witch is optimized for search once the second argument is set to true
     *
     * @param array   $parameters
     * @param boolean $isSearch
     *
     * @throws DataMapperException
     *
     * @return self
     */
    public function bindParameters(array $parameters, bool $isSearch = false): self;

    /**
     * Return the number of rows affected by a DELETE, INSERT, UPDATE or SELECT query
     *
     * @return integer
     */
    public function numRows(): int;

    /**
     * Executing the prepared query
     *
     */
    public function execute();

    /**
     * Return a single database row as an object
     *
     * @return object
     */
    public function fetch(): object;

    /**
     * Return all the rows with in the database as array
     *
     * @return array
     */
    public function fetchAll(): array;

    /**
     * Return the last inserted id
     *
     * @return integer
     */
    public function getLastId(): int;
}
