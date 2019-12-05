<?php
class DB_Connect {
    private $host = "localhost" ;
    private $db_name = "mahasiswa";
    private $username = "raspberry";
    private $password = "123456789";
    private $conn;
 
    // koneksi ke database
    public function connect() {
        require_once 'config.php';
        $this->conn = null;

        // koneksi ke mysql database
        // $this->conn = new PDO(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
         
        // return database handler
        return $this->conn;
    }
}
 
?>