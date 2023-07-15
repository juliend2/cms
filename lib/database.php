<?php

class Database
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;

    public function __construct($host, $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        $this->connect();
    }

    private function connect()
    {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->connection->connect_errno) {
            throw new Exception("Failed to connect to MySQL: " . $this->connection->connect_error);
        }
    }

    public function fetchObjects($sql, $params = [])
    {
        $statement = $this->prepareStatement($sql, $params);
        $statement->execute();
        $result = $statement->get_result();

        $objects = [];
        while ($object = $result->fetch_object()) {
            $objects[] = $object;
        }

        $statement->close();
        return $objects;
    }

    public function fetchObject($sql, $params = [])
    {
        $statement = $this->prepareStatement($sql, $params);
        $statement->execute();
        $result = $statement->get_result();
        $object = $result->fetch_object();

        $statement->close();
        return $object;
    }

    public function fetchField($sql, $params = [])
    {
        $statement = $this->prepareStatement($sql, $params);
        $statement->execute();
        $result = $statement->get_result();
        $field = $result->fetch_array(MYSQLI_NUM)[0];

        $statement->close();
        return $field;
    }

    private function prepareStatement($sql, $params)
    {
        $statement = $this->connection->prepare($sql);
        if ($statement === false) {
            throw new Exception("Failed to prepare statement: " . $this->connection->error);
        }

        if (!empty($params)) {
            $types = "";
            $values = [];

            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= "i";
                } elseif (is_float($param)) {
                    $types .= "d";
                } elseif (is_string($param)) {
                    $types .= "s";
                } else {
                    $types .= "b";
                }

                $values[] = $param;
            }

            $statement->bind_param($types, ...$values);
        }

        return $statement;
    }
}
