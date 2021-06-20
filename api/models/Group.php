<?php 
include "../config/database.php";

class Group {

	private $conn;
    private $table_name = "t_group";
 
    // object properties
    public $grp_id;
    public $grp_name;
    public $grp_stadium1;
    public $grp_stadium2;
    public $grp_stadium3;
    public $grp_stadium4;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getAllGroup(){
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

    function getGroupById(){
        $query = "SELECT
                    *
              FROM
                " . $this->table_name . "
            WHERE
                GRP_ID = ?";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->grp_id);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->grp_id = $row['GRP_ID'];
    $this->grp_name = $row['GRP_NAME'];
    $this->grp_stadium1 = $row['GRP_STADIUM1'];
    $this->grp_stadium2 = $row['grp_stadium2'];
    $this->grp_stadium3 = $row['GRP_STADIUM3'];
    $this->grp_stadium4 = $row['grp_stadium4'];
   
    
    return $this; 
    }
    
}