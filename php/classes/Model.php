<?php

require_once __DIR__ . '/Database.php';

abstract class Model
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var string
     */
    protected $primaryKey = '';

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
     * Model constructor.
     *
     * @param $id
     * @throws Exception
     */
    public function __construct($id = null)
    {
        if (isset($id)) {
            $result = Database::query(
                "SELECT * FROM {$this->table} WHERE {$this->primaryKey}=?",
                [$id]
            );

            if (!isset($result[0])) {
                throw new Exception('Model not found.');
            }

            foreach ($this->columns as $name) {
                $this->originals[$name] = $result[0][$name];
            }

            $this->isNew = false;
        } else {
            foreach ($this->columns as $name) {
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
        if (!in_array($name, $this->columns)) {
            throw new Exception("Field '{$name}' was not found.");
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
        if (!in_array($name, $this->columns)) {
            throw new Exception("Field '{$name}' was not found.");
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
            $paramList[] = $this->originals[$this->primaryKey];

            Database::query(
                "UPDATE {$this->table} SET " . implode(', ', $setList) . " WHERE {$this->primaryKey}=?",
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
                "INSERT INTO {$this->table} (" . implode(', ', $colList) . ') '
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
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey}=?",
            [$id]
        );

        foreach ($this->columns as $name) {
            $this->originals[$name] = $result[0][$name];
        }

        $this->changed = [];
        $this->isNew = false;
    }
}
