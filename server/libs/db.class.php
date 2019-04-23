<?php

class DB
{

    protected $connection;
    public $error;

    public function __construct($db_host, $db_user, $db_password, $db_database)
    {
        $this->connection = new mysqli($db_host, $db_user, $db_password, $db_database);

        $this->connection->query("SET NAMES UTF-8");
        $this->connection->query("SET collation_connection = utf8_general_ci");
        $this->connection->query("SET CHARACTER SET utf8");

        if (mysqli_connect_error()) {
            throw new Exception('Error DataBase connection');
        }
    }

    public function query($sql)
    {
        if (!$this->connection) return false;

        $result = $this->connection->query($sql);

        if ($err = mysqli_error($this->connection)) {
            $this->error = $err;
            return false;
        }

        if (is_bool($result)) return $result;

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    public function escape($str)
    {
        return mysqli_escape_string($this->connection, $str);
    }

    /**
     * @return mysqli
     */
    public function getConnection()
    {
        return $this->connection;
    }

}