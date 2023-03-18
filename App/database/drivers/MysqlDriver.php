<?php

namespace App\database\drivers;

use http\Exception;

class MysqlDriver implements DriverContract
{
    /** @var \PDO  */
    private $_connection;


    public function __construct(){
        $this->createConnection();
    }

    /**
     * @inheritDoc
     */
    public function createConnection()
    {
        $host = _env('HOST', 'localhost');
        $dbname = _env('DB_NAME', 'noban');
        $username = _env("DB_USER_NAME", "root");
        $password = _env("DB_PASSWORD", "");
        $charet = _ENV("DB_CHARSET", "utf8");

        try{
            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charet";
            $pdo = new \PDO($dsn, $username, $password);

            $this->_connection = $pdo;

        }catch (\Exception){
            throw new \Exception('Connect to Mysql is Failed');
        }


    }

    /**
     * @inheritDoc
     */
    public function setConnection(mixed $connection)
    {
        $this->_connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert(string $tableName, array $data): bool
    {
        $attributeString = '(' . implode(', ', array_keys($data)) . ')';
        $attributesBindParam = $this->getBindParamsString($data);

        $sql = "INSERT INTO {$tableName} {$attributeString} VALUES $attributesBindParam";
        return  $this->_connection->prepare($sql)->execute(array_values($data));
    }

    /**
     * @inheritDoc
     */
    public function select(string $tableName, ?array $fields = ['*'], ?array $conditions = [], ? array $orderBy = ['id' => SORT_DESC]): ?array
    {
//        $fieldsString = implode(',', $fields);
//        $sql = "SELECT {$fieldsString} FROM {$tableName}";
//
//        if(! empty($conditions)){
//            $sql .= " WHERE ";
//
//            foreach($conditions as $key => $condition){
//                if(!is_array($condition) && in_array(strtolower($condition), ['or', 'and'])){
//                    $sql .= ' '. strtoupper($condition) . ' ';
//                    continue;
//                }
//
//                if(is_int($key) && $key !== 0){
//                    $sql .= ' and ';
//                }
//                $sql .=  $this->generateWhereClause($condition);
//
//            }
//
//        }
//
//        dd($sql);
    }

    /**
     * @inheritDoc
     */
    public function update(string $tableName, array $data, ?array $conditions): bool
    {
        // TODO: Implement update() method.
        return false;
    }

    public function query(string $query)
    {
        $res = $this->_connection->query($query, \PDO::FETCH_ASSOC);
        return $res->fetchAll();
    }

    /**
     * @inheritDoc
     */
    public function remove(string $tableName, array $condition): bool
    {
        // TODO: Implement remove() method.
        return false;
    }

    private function getBindParamsString($data){
        $bindparams = array_fill(0, count($data), '?');
        return '(' . implode(', ', $bindparams) . ')';
    }



    /**
     * Generate a WHERE clause based on the given condition array.
     *
     * @param array $condition The condition array, with format [operator, field, value] or [field => value].
     * @return string The generated WHERE clause for use in a SQL query.
     */
    private function generateWhereClause(array $condition): string
    {
        $operatorMap = [
            '>' => '>',
            '<' => '<',
            '>=' => '>=',
            '<=' => '<=',
            '!=' => '<>',
            '<>' => '<>',
            '=' => '=',
            'in' => 'IN',
            'not in' => 'NOT IN',
            'like' => 'LIKE',
            'not like' => 'NOT LIKE'
        ];

        if (count($condition) == 1) {
            $key = key($condition);
            $value = current($condition);
            return "{$key}='{$value}'";
        } else {
            $operator = $operatorMap[$condition[0]];
            $field = $condition[1];
            $value = is_array($condition[2]) ? '(' . implode(',', $condition[2]) . ')' : "'{$condition[2]}'";
            return "{$field} {$operator} {$value}";
        }
    }



}