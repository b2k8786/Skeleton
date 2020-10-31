<?php

namespace Core;
/*
 * Database mapper
 * @version 0.1
 */

/**
 * @property string $table holds table name
 * @property mixed $columns
 * @property mixed $where
 * @property object $pdo PDO class object
 * @property string $prefix prefix to table names
 * @property object $data holds rows from a select query
 * @property object $saveData holds data to be saved
 * @property array $fields internal operations
 * @property array $values internal operations
 * @property string $query Select query
 */
class SupORM
{

    protected $table;
    private $columns = '*';
    private $where   = '';
    private $pdo;
    protected $prefix = '';
    protected $sufix = '';
    private $data;
    private $saveData;
    private $fields;
    private $values;
    protected $query;
    protected $lastQueryObject;

    function __construct()
    {
        $rf             = new \ReflectionClass($this);
        $this->table    = $this->prefix . $rf->getShortName() . $this->sufix;
        try {
            $this->pdo      = new \PDO(DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $ex) {
            _dump($ex);
        }
        $this->data     = new \stdClass();
        $this->saveData = new \stdClass();
    }

    function getTable()
    {
        return $this->table;
    }

    function select(...$columns)
    {
        if (is_array($columns[0]))
            $columns       = $columns[0];
        $this->columns = '`' . implode('`, `', $columns) . '`';
        return $this;
    }

    function where(...$condition)
    {
        if (is_array($condition[0]))
            $condition   = $condition[0];
        $this->where = " WHERE " . implode(' AND ', $condition);
        return $this;
    }

    private function get()
    {
        $this->query = "SELECT $this->columns FROM $this->table $this->where";
        $this->query = "SELECT $this->columns FROM users $this->where";
        $rs          = $this->pdo->query($this->query);
       
        if (is_object($rs)) {
            $this->lastQueryObject = $rs;
            $this->data            = $rs->fetch($this->pdo::FETCH_OBJ);
        } else {
            $debug = debug_backtrace();
            throw new \Exception("{$debug[0]['file']} Line:{$debug[0]['line']} ");
        }

        return $this->data;
    }

    function getAll()
    {
        $rs = $this->pdo->query("SELECT $this->columns FROM $this->table $this->where");
        if (is_object($rs)) {
            $this->lastQueryObject = $rs;
            return $rs->fetchAll($this->pdo::FETCH_OBJ);
        } else {
            $debug = debug_backtrace();
            throw ("Error in Query {$debug[0]['file']} Line:{$debug[0]['line']}");
        }
    }

    function save()
    {
        $dataArray             = get_object_vars($this->saveData);
        $this->fields          = '`' . implode('`,`', array_keys($dataArray)) . '`';
        $this->values          = implode(',', array_fill(0, count($dataArray), '?'));
        $this->query           = "INSERT INTO $this->table ($this->fields) VALUES ($this->values)";
        $stmt                  = $this->pdo->prepare($this->query);
        $stmt->execute(array_values($dataArray));
        $this->lastQueryObject = $stmt;
        return $this->lastQueryObject;
    }

    function update()
    {
        $dataArray             = get_object_vars($this->saveData);
        $this->fields          = implode('=?,', array_keys($dataArray)) . '=?';
        $this->query           = "UPDATE $this->table SET $this->fields $this->where";
        $stmt                  = $this->pdo->prepare($this->query);
        $stmt->execute(array_values($dataArray));
        $this->lastQueryObject = $stmt;
    }

    function __get($field)
    {
        $this->columns = $field;
        try {
            $this->get();
            return $this->data->{$field};
        } catch (\PDOException $ex) {
            _dump("<strong>'$field' not found</strong>");
            _dump($ex);
        }
    }

    function __set($name, $value)
    {
        return $this->saveData->{$name} = $value;
    }

    function dump()
    {
        $this->lastQueryObject->debugDumpParams();
    }

    function affectedRows()
    {
        return $this->lastQueryObject->rowCount();
    }

    function lastQuery()
    {
        return $this->query;
    }
}
