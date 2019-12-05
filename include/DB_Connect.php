<?php
class DB_Connect {
    // private $host = "localhost" ;
    // private $db_name = "mahasiswa";
    // private $username = "raspberry";
    // private $password = "123456789";
    // private $conn;
 
    // koneksi ke database
    public function connect() {
        require_once 'config.php';
        $this->conn = null;

        // koneksi ke mysql database
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
         
        // return database handler
        return $this->conn;
    }
}
 
?>