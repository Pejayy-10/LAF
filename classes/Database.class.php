<?php

class Database
{
    private $host = 'localhost'; // Replace with your host
    private $db_name = 'campus_lost_found'; // Replace with your database name
    private $username = 'root'; // Replace with your database username
    private $password = ''; // Replace with your database password
    private $conn;

    // Connect to the database
    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }

        return $this->conn;
    }
}

?>
