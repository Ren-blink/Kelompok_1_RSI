<?php

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $db_name = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            // DB handler null agar aplikasi tetap berjalan menggunakan mock data
            $this->dbh = null;
        }
    }

    public function isConnected()
    {
        return $this->dbh !== null;
    }

    public function getError()
    {
        return $this->error;
    }

    public function query($query)
    {
        if (!$this->dbh) {
            return false;
        }
        $this->stmt = $this->dbh->prepare($query);
        return true;
    }

    public function bind($param, $value, $type = null)
    {
        if (!$this->stmt) {
            return;
        }

        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        if (!$this->stmt) {
            return false;
        }
        return $this->stmt->execute();
    }

    public function resultSet()
    {
        if (!$this->execute()) {
            return [];
        }
        return $this->stmt->fetchAll();
    }

    public function single()
    {
        if (!$this->execute()) {
            return null;
        }
        return $this->stmt->fetch();
    }

    public function rowCount()
    {
        return $this->stmt ? $this->stmt->rowCount() : 0;
    }
}
