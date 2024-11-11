<?php

class Database {
    private $server_name = "localhost";
    private $username = "root";
    private $password = ""; //for MAMP users, please use 'root'
    private $db_name = "the_company";
    protected $conn; // we want to allow other inheriting classes to access the connection

    public function __construct()
    {
        $this->conn = new mysqli($this->server_name, $this->username, $this->password, $this->db_name);

        if ($this->conn->connect_error) {
            die('Unable to connect to the datase: ' . $this->conn->connect_error);
        }

    }
}