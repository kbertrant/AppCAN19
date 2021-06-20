<?php 
/**
 * 
 */
class Player_Line {
	private $conn;
    private $table_name = "t_player_line";
 
    // object properties
    public $line_id;
    public $line_code;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getAllLine(){
    	$query = "SELECT
                *
            FROM
                " . $this->table_name . "";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // execute query
    $stmt->execute();
    return $stmt;
    }

    function getPositionById(){
        $query = "SELECT
                    *
              FROM
                " . $this->table_name . "
            WHERE
                LINE_ID = ?";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->line_id);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->line_id = $row['LINE_ID'];
    $this->line_code = $row['LINE_CODE'];
   
    return $this; 
    }
}