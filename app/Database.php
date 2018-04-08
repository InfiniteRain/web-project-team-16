<?php

namespace WebTech\Hospital;

/**
 * Database class for simpler query execution.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
class Database
{
    /**
     * PDO object representing the connection.
     *
     * @var \PDO
     */
    private static $connection;

    /**
     * Connects to the database.
     *
     * @throws \PDOException
     */
    private static function connect()
    {
        if (isset(self::$connection)) {
            return;
        }

        $connection = new \PDO(
            'mysql:host=' . CONFIG['host'] . ';dbname=' . CONFIG['dbname'],
            CONFIG['username'],
            CONFIG['password']
        );

        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        self::$connection = $connection;
    }

    /**
     * Executes a query, accepts parameters as an array.
     *
     * @param string $query
     * @param array $params
     * @return array
     * @throws \PDOException
     */
    public static function query(string $query, array $params = [])
    {
        self::connect();

        $statement = self::$connection->prepare($query);
        $statement->execute($params);
        if ($statement->columnCount() > 0) {
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Returns the last insert ID.
     *
     * @return string
     */
    public static function lastInsertId()
    {
        return self::$connection->lastInsertId();
    }
}
