<?php 
/**
 * 
 */
class Team_Sta
{

    const TBLE_NB = 23;

    // database connection and table name
    private $conn;
    private $table_name = "t_team_sta";
 
    // object properties
    public $team_sta_id;
    public $team_id;
    public $rnk_id;
    public $grp_id;
    public $team_win;
    public $team_los;
    public $team_draw;
    public $team_pts;
    public $team_sco;
    public $team_con;
    public $team_avg;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getAllPlayerSta(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " LIMIT " . TBLE_NB;
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }

    function getTeamStaById(){
        // query to read single record
        $query = "SELECT
                        *
                  FROM
                    " . $this->table_name . "
                WHERE
                    TEAM_STA_ID = ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->team_sta_id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->team_sta_id = $row['TEAM_STA_ID'];
        $this->team_id = $row['TEAM_ID'];
        $this->rnk_id = $row['RNK_ID'];
        $this->grp_id = $row['GRP_ID'];
        $this->team_win = $row['TEAM_WIN'];
        $this->team_los = $row['TEAM_LOS'];
        $this->team_draw = $row['TEAM_DRAW'];
        $this->team_pts = $row['TEAM_PTS'];
        $this->team_sco = $row['TEAM_SCO'];
        $this->team_con = $row['TEAM_CON'];
        $this->team_avg = $row['TEAM_AVG'];

        return $this;
	}

}