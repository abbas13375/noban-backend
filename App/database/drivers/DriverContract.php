<?php

namespace App\database\drivers;

interface DriverContract
{

    /**
     * create connection to driver such as Mysql, MongoDb, etc
     * @return null
     * @throws \Exception;
     */
    public function createConnection();


    /**
     * set object connection driver to a property.
     * @return null;
     */
    public function setConnection(mixed $connection);


    /**
     * get connection object driver for use.
     * @return object
     */
    public function getConnection();


    /**
     * save data in db.
     * @param array $data
     * @return bool
     */
    public function insert(string $tableName, array $data): bool;


    /**
     * select data from db.
     * @param array|null $conditions
     * @return array|null
     */
    public function select(string $tableName, ? array $fields = null, ? array $conditions = [], ? array $orderBy = []): ?array;


    /**
     * update records.
     * @param array $data
     * @param array|null $conditions
     * @return bool
     */
    public function update(string $tableName, array $data, ? array $conditions): bool;

    /**
     * remove one field or multiple fields with condtion.
     * @param array $condition
     * @return bool
     */
    public function remove(string $tableName, array $condition): bool;

    /**
     * run any query
     * @param string $query
     * @return mixed
     */
    public function query(string $query);
}