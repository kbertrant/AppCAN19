<?php 
/**
 * 
 */
class Player
{

// database connection and table name
    private $conn;
    private $table_name = "t_player";
 
    // object properties
    public $ply_id;
    public $team_id;
    public $line_id;
    public $ply_name;
    public $ply_number;
    public $ply_gkp_val;
    public $ply_def_val;
    public $ply_mid_val;
    public $ply_att_val;
    public $ply_val;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getAllPlayer(){
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

function getPlayerById(){
	// query to read single record
    $query = "SELECT
					*
			  FROM
                " . $this->table_name . "
            WHERE
                PLY_ID = ?";

    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->ply_id);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->ply_id = $row['PLY_ID'];
    $this->team_id = $row['TEAM_ID'];
    $this->line_id = $row['LINE_ID'];
    $this->ply_name = $row['PLY_NAME'];
    $this->ply_nbr = $row['PLY_NBR'];
    $this->ply_gkp_val = $row['PLY_GKP_VAL'];
    $this->ply_def_val = $row['PLY_DEF_VAL'];
    $this->ply_mid_val = $row['PLY_MID_VAL'];
    $this->ply_att_val = $row['PLY_ATT_VAL'];
    $this->ply_val = $row['PLY_VAL'];

    return $this; 

	}

    function getAllpower(){

    $query = "SELECT SUM(PLY_VAL) FROM  " . $this->table_name . " WHERE TEAM_ID = ?";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // bind id of product to be updated
    $stmt->bindParam(1, $this->team_id);
    // execute query
    $stmt->execute();
    //var_dump($stmt);
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row; 
    }


     function getPlayersByTeam(){
 
    // select all query
     $query = "SELECT
                    *
              FROM
                " . $this->table_name . "
            WHERE
                TEAM_ID = ?";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(1, $this->team_id);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

}