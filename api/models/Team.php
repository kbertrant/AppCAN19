<?php
Class Team{
	// database connection and table name
    private $conn;
    private $table_name = "t_team";
 
    // object properties
    public $team_id;
    public $team_name;
    public $team_code;
    public $team_flag;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function read(){
 
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

function getTeamById(){
	// query to read single record
    $query = "SELECT
					*
			  FROM
                " . $this->table_name . "
            WHERE
                TEAM_ID = ?";

    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->team_id);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->team_id = $row['TEAM_ID'];
    $this->team_name = $row['TEAM_NAME'];
    $this->team_code = $row['TEAM_CODE'];
    $this->team_flag = $row['TEAM_FLAG'];
    return $this;
}

function getIdTeamByName(){
    // query to read single record
    $query = "SELECT
                    TEAM_ID
              FROM
                " . $this->table_name . "
            WHERE
                TEAM_NAME = ?";

    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->team_name);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->team_id = $row['TEAM_ID'];
}

function getIdGroupByTeamStaId(){
    // query to read single record
    $query = "SELECT
                    GRP_ID
              FROM
                " . $this->table_name . "
            WHERE
                TEAM_STA_ID = ?";

    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->TEAM_STA_ID);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->grp_id = $row['GRP_ID'];
}
}


