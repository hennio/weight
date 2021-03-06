<?php

class Stores_SQLite
{

    private $_db;

    public function __construct()
    {
        $this->_db = new PDO("sqlite:" . self::_getPath());
    }

    public function query($query, $data, $returnOne = false)
    {

        foreach ($data as $key => $val) {
            $query = str_replace($key, $this->_db->quote($val), $query);
        }

        $res = $this->_db->query($query);

        if (!$res) {
            $error = $this->_db->errorInfo();
            die('<pre>MySQL Fatal Error: ' . $error[2] . "\n" . 'query="' . $query . '"</pre>');
        }

        $data = $res->fetchAll(PDO::FETCH_ASSOC);

        if ($returnOne && count($data) == 1) {
            return $data[0];
        }

        return $data;
    }

    public function insert($table, $data)
    {
        $keys = array_keys($data);

        foreach ($data as $key => $val) {
            $data[':' . $key] = $val;
            unset($data[$key]);
        }

        $query = "INSERT INTO $table (" . implode(", ", $keys) . ") VALUES (" .
        implode(", ", array_keys($data)) . ");";

        return $this->query($query, $data);
    }

    private static function _getPath()
    {
        $config = Config::get('system');
        return $config->sqlite_path;
    }

}
