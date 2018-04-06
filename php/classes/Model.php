<?php

require_once __DIR__ . '/Database.php';

abstract class Model
{
    /**
     * @var string
     */
    protected static $table;

    /**
     * @var array
     */
    protected static $columns = [];

    /**
     * @var string
     */
    protected static $primaryKey = '';

    /**
     * @var array
     */
    private $originals = [];

    /**
     * @var array
     */
    private $changed = [];

    /**
     * @var boolean
     */
    private $isNew;

    /**
     * Returns an array of models filtered by a WHERE clause.
     *
     * @param $query
     * @param $params
     * @return array
     * @throws PDOException
     */
    public static function where($query, $params = [])
    {
        $result = Database::query(
            'SELECT * FROM ' . static::$table . ' WHERE ' . $query,
            $params
        );

        $models = [];
        foreach ($result as $row) {
            $models[] = new User($row);
        }

        return $models;
    }

    /**
     * Finds a model by its primary key.
     *
     * @param $id
     * @return User
     * @throws Exception
     */
    public static function find($id)
    {
        $result = Database::query(
            'SELECT * FROM ' . static::$table . ' WHERE ' . static::$primaryKey . '=?',
            [$id]
        );

        if (!isset($result[0])) {
            throw new Exception('Model not found.');
        }

        return new User($result[0]);
    }

    /**
     * Model constructor.
     *
     * @param array $vals
     */
    public function __construct(array $vals = null)
    {
        if ($vals !== null) {
            foreach (static::$columns as $name) {
                $this->originals[$name] = $vals[$name];
            }

            $this->isNew = false;
        } else {
            foreach (static::$columns as $name) {
                $this->originals[$name] = null;
            }

            $this->isNew = true;
        }
    }

    /**
     * Magic method for the column value change.
     *
     * @param string $name
     * @param $value
     * @throws Exception
     */
    public function __set(string $name, $value)
    {
        if (!in_array($name, static::$columns)) {
            throw new Exception("Column '{$name}' was not found.");
        }

        if ($value === $this->originals[$name]) {
            return;
        }

        $this->changed[$name] = $value;
    }

    /**
     * Magic method for column values access.
     *
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function __get(string $name)
    {
        if (!in_array($name, static::$columns)) {
            throw new Exception("Column '{$name}' was not found.");
        }

        if (isset($this->changed[$name])) {
            return $this->changed[$name];
        }

        return $this->originals[$name];
    }

    /**
     * Saves the changes to the user into the database.
     *
     * @throws PDOException
     */
    public function save()
    {
        if (!$this->isNew) {
            if (count($this->changed) === 0) {
                return;
            }

            $setList = [];
            $paramList = [];
            foreach ($this->changed as $key => $value) {
                $setList[] = $key . '=?';
                $paramList[] = $value;
            }
            $paramList[] = $this->originals[static::$primaryKey];

            Database::query(
                'UPDATE ' . static::$table . ' SET ' . implode(', ', $setList) . ' WHERE '
                    . static::$primaryKey . '=?',
                $paramList
            );
        } else {
            $colList = [];
            $qm = [];
            $valList = [];
            foreach ($this->changed as $key => $value) {
                $colList[] = $key;
                $qm[] = '?';
                $valList[] = $value;
            }

            Database::query(
                'INSERT INTO ' . static::$table . ' (' . implode(', ', $colList) . ') '
                . 'VALUES (' . implode(', ', $qm) . ')',
                $valList
            );
        }

        if ($this->isNew) {
            $id = Database::lastInsertId();
        } else {
            $id = isset($this->changed['id']) ? $this->changed['id'] : $this->originals['id'];
        }

        $result = Database::query(
            'SELECT * FROM ' . static::$table . ' WHERE ' . static::$primaryKey . '=?',
            [$id]
        );

        foreach (static::$columns as $name) {
            $this->originals[$name] = $result[0][$name];
        }

        $this->changed = [];
        $this->isNew = false;
    }
}
