<?php
class Database{
 
    // specify your own database credentials
    private $host = 'db770926078.hosting-data.io';
    private $db_name = 'db770926078';
    private $username = 'dbo770926078';
    private $password = 'BD_appcan19';
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=$host; dbname=$db_name;", $username, $password);
            //$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}