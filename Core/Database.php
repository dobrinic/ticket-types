<?php

namespace Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;
    private PDOStatement $statement;

    public function __construct()
    {
        $config = require base_path('/config/database.php');
        $dsn = "mysql:host={$config['mysql']['host']};port={$config['mysql']['port']};dbname={$config['mysql']['dbname']};charset={$config['mysql']['charset']}";

        try {
            $this->connection = new PDO($dsn, $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['options']);
        } catch (PDOException $e) {
            // log($e->getMessage(), (int)$e->getCode());
            // return appropriate message to the user that something went wrong instead of throwing an exception
            throw new PDOException('DB connection is not available: ' . $e->getMessage(), (int)$e->getCode());
        }
    }

    public static function get(): Database
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function connection(): PDO
    {
        return $this->connection;
    }

    public function query(string $sql, $params = []): Database
    {
        $this->statement = $this->connection->prepare($sql);
        $this->statement->execute($params);
        return $this;
    }

    public function lastId(): false|string
    {
        return $this->connection->lastInsertId();
    }

    public function all(): false|array
    {
        return $this->statement->fetchAll();
    }

    public function find()
    {
        return $this->statement->fetch();
    }

    public function findOrFail()
    {
        $result = $this->find();

        if (! $result) {
            abort();
        }

        return $result;
    }
}