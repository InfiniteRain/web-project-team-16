<?php

class Database
{
    /**
     * PDO object representing the connection.
     *
     * @var PDO
     */
    private static $connection;

    /**
     * Connects to the database.
     *
     * @throws PDOException
     */
    private static function connect()
    {
        if (isset(self::$connection)) {
            return;
        }

        $connection = new PDO(
            'mysql:host=' . CONFIG['host'] . ';dbname=' . CONFIG['dbname'],
            CONFIG['username'],
            CONFIG['password']
        );

        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$connection = $connection;
    }

    /**
     * Executes a query.
     *
     * @param string $query
     * @param mixed ...$params
     * @return array
     * @throws PDOException
     */
    public static function query(string $query, ...$params)
    {
        self::connect();

        $statement = self::$connection->prepare($query);
        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
