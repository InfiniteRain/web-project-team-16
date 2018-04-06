<?php

require_once __DIR__ . '/Database.php';

/**
 * Abstract model class.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
abstract class Model
{
    /**
     * @var string Name of the table.
     */
    protected static $table;

    /**
     * @var array List of column names.
     */
    protected static $columns = [];

    /**
     * @var string Name of the primary key column.
     */
    protected static $primaryKey = '';

    /**
     * @var array Stores original column values (pre save()).
     */
    private $originals = [];

    /**
     * @var array Stores all the changes to the column values.
     */
    private $changed = [];

    /**
     * @var boolean
     */
    private $isNew;

    /**
     * @var boolean
     */
    private $isDeleted;

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

        $this->isDeleted = false;
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
        if ($this->isDeleted) {
            throw new Exception('Model is deleted.');
        }

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
        if ($this->isDeleted) {
            throw new Exception('Model is deleted.');
        }

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
     * @throws Exception
     */
    public function save()
    {
        if ($this->isDeleted) {
            throw new Exception('This model is deleted.');
        }

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

    /**
     * Deletes the model.
     *
     * @throws Exception
     * @throws PDOException
     */
    public function delete()
    {
        if ($this->isNew) {
            throw new Exception('This model is not saved yet.');
        }

        if ($this->isDeleted) {
            throw new Exception('This model is already deleted.');
        }

        Database::query(
            'DELETE FROM ' . static::$table . ' WHERE ' . static::$primaryKey . '=?',
            [$this->{static::$primaryKey}]
        );

        $this->isDeleted = true;
    }
}
