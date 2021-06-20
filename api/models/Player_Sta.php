<?php 
/**
 * 
 */
class Player_Sta
{

// database connection and table name
    private $conn;
    private $table_name = "t_player_sta";
 
    // object properties
    public $ply_id_sta;
    public $ply_id;
    public $ply_tit;
    public $ply_sub;
    public $ply_shp;
    public $ply_inj;
    public $ply_crd;
    public $ply_dsq;
    public $ply_sco;
    public $ply_ass;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getAllPlayerSta(){
    // select all query
    $query = "SELECT
                *
            FROM
                " . $this->table_name . " LIMIT 23";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // execute query
    $stmt->execute();
    return $stmt;
}

function getPlayerStaById(){
	// query to read single record
    $query = "SELECT
					*
			  FROM
                " . $this->table_name . "
            WHERE
                PLY_ID_STA = ?";

    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->ply_id_sta);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->ply_id_sta = $row['PLY_ID_STA'];
    $this->ply_id = $row['PLY_ID'];
    $this->ply_tit = $row['PLY_TIT'];
    $this->ply_sub = $row['PLY_SUB'];
    $this->ply_shp = $row['PLY_SHP'];
    $this->ply_inj = $row['PLY_INJ'];
    $this->ply_crd = $row['PLY_CRD'];
    $this->ply_dsq = $row['PLY_DSQ'];
    $this->ply_sco = $row['PLY_SCO'];
    $this->ply_ass = $row['PLY_ASS'];

    return $this; 

	}


     function getPlayerStaByTeam(){
    // select all query
     $query = "SELECT
                    *
              FROM
                " . $this->table_name . "
            WHERE
                PLY_ID = ?";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->ply_id);
    // execute query
    $stmt->execute();
    return $stmt;
}

}