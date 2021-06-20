<?php
Class Tactic{
	// database connection and table name
    private $conn;
    private $table_name = "t_tactic";
 
    // object properties
    public $tac_id;
    public $tac_name;
    public $tac_code;
    public $tac_bon;
    public $tac_opp;
    public $tac_apt;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

function getAllTactic(){
 
    // select all query
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

function getTacticById(){
    // query to read single record
    $query = "SELECT
                    *
              FROM
                " . $this->table_name . "
            WHERE
                TAC_ID = ?";

    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->tac_id);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->tac_id = $row['TAC_ID'];
    $this->tac_name = $row['TAC_NAME'];
    $this->tac_code = $row['TAC_CODE'];
    $this->tac_bon = $row['TAC_BON'];
    $this->tac_opp = $row['TAC_OPP'];
    $this->tac_apt = $row['TAC_APT'];
    
    return $this; 

    }

}
