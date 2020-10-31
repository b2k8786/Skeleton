<?php
namespace Lib;

/**
 * Class for database handling;
 * @property Dbase $instance
 * @version 2.5
 */
class Dbase extends \mysqli {
    public $query            = null;
    private $exc             = null;
    private static $instance = null;

    /**
     *
     * DB class constructor for PDO operations.
     * containing functions for database, based on PDO
     */
    function __construct() {
        try {
            parent::__construct(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        } catch (\mysqli_sql_exception $Ex) {
            print_r($Ex->getMessage());
        }
        self::$instance = & $this;
    }

    /**
     *
     * @param String $tableName
     * @param Associative Array ($key=>$val)  $data
     * @return bool
     */
    public static function &getInstance() {
        if (!is_object(self::$instance)) {
            self::$instance = new Dbase();
        }
        return self::$instance;
    }

    /**
     * Insert data to tables
     * @param string $tableName
     * @param array $data array of array for multiple rows [[],[]]
     * @return $this
     */
    function insert($tableName, $data) {
        if (!empty($data[0]) && is_array($data[0])) {
            $fields = implode('`,`', array_keys($data[0]));
            $values = [];
            foreach ($data as $d) {
                array_push($values, "('" . implode("','", array_values($d)) . "')");
            }
            $values      = implode(",", $values);
            $this->query = "INSERT INTO `$tableName` (`$fields`) values $values";
        } else {
            $fields      = implode('`,`', array_keys($data));
            $values      = implode("','", array_values($data));
            $this->query = "INSERT INTO `$tableName` (`$fields`) values ('$values')";
        }

        return $this;
    }

    /**
     *
     * Insert multiple rows
     *
     * @param type $dataArray
     */
    function insertRows($tableName, $dataArray) {
        if (is_array($dataArray[0])) {
            $fields = implode('`,`', array_keys($dataArray[0]));

            $values = [];
            foreach ($dataArray as $data) {
                array_push($values, "('" . implode("','", array_values($data)) . "')");
            }
        }
        $values      = implode(",", $values);
        $this->query = "INSERT INTO `$tableName` (`$fields`) values $values";
        return $this;
    }

    /**
     *
     * @param String $tableName
     * @return Boolean
     */
    function update($tableName) {
        $this->query = "UPDATE $tableName";
        return $this;
    }

    /**
     *
     * @return Object
     */
    function run() {
        $sql = parent::query($this->query);
        if (is_object($sql)) {
            return $this->exc = $sql;
        } else {
            $debug = debug_backtrace();
            print("Error in Query {$debug[0]['file']} Line:{$debug[0]['line']}");
            return false;
        }
    }

    /**
     *
     * @param Array $data
     * @return Object Db
     */
    function set($data) {
        $set         = implode(',', $data);
        $this->query .= ' SET ' . $set;
        return $this;
    }

    /**
     * Print Dump of sql Query
     */
    function dump_query() {
        echo $this->query;
    }

    /**
     *
     * @param Array $tableName
     * @return object
     */
    function from($tableName) {
        $tabels      = implode(',', $tableName);
        $this->query = "SELECT * FROM $tabels";
        return $this;
    }

    /**
     *
     * @param String column name
     * @return Object
     */
    function count($column) {
        $this->query = str_replace('*', "COUNT($column) AS count", $this->query);
        return $this;
    }

    /**
     *
     * @param Array $fieldNames
     * @return Object
     */
    function select($fieldNames) {
        $fields      = implode(',', $fieldNames);
        $this->query = str_replace('*', $fields, $this->query);
        return $this;
    }

    /**
     *
     * @param  Array $conditions
     * @return Object
     */
    function where($conditions) {
        if (is_array($conditions) && count($conditions)) {
            $condition   = implode(' AND ', $conditions);
            $this->query .= " WHERE $condition";
        }
        return $this;
    }

    /**
     *
     * @param String $orderBy Field name by which should be order by.
     * @param String $order Data Order as ASC / DESC;
     * @return Object
     */
    function orderBy($orderBy, $order = 'ASC') {
        $this->query .= " ORDER BY $orderBy $order";
        return $this;
    }

    /**
     *
     * @param String $groupBy
     * @return object
     */
    function groupBy($groupBy) {
        $this->query .= " GROUP BY $groupBy ";
        return $this;
    }

    /**
     *
     */
    function get() {
        $this->exc = parent::query($this->query);
        if (!$this->exc) {
            $debug = debug_backtrace();
            throw new \Exception($this->error);
        }
        return $this;
    }

    /**
     *
     * @return Array rows as associative Array
     */
    function row() {
        return $this->exc->fetch_assoc();
    }

    function rows() {
        return $this->exc->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Apply limit
     */
    function limit($start, $end) {
        if (isset($start) && isset($end))
            $this->query .= " LIMIT $start,$end";
        return $this;
    }

    /**
     *
     * @param String $query
     * @return Object \Db
     */
    function query($query) {
        $this->query = $query;
        return $this;
    }

}
