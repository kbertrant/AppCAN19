<?php 
/**
 * 
 */
class Match
{

// database connection and table name
    private $conn;
    private $table_name = "t_match";
 
    // object properties
    public $match_id;
    public $match_t1_tac;
    public $match_t2_tac;
    public $match_t1;
    public $match_t2;
    public $match_code;
    public $match_grp;
    public $match_t1_val;
    public $match_t1_apt;
    public $match_t1_bon;
    public $match_t1_sco;
    public $match_t1_att_bon;
    public $match_t1_mid_bon;
    public $match_t1_def_bon;
    public $match_t2_val;
    public $match_t2_apt;
    public $match_t2_bon;
    public $match_t2_sco;
    public $match_t2_att_bon;
    public $match_t2_mid_bon;
    public $match_t2_def_bon;
    public $match_score;
    public $match_winner;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getAllMatch(){
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

function getMatchById(){
	// query to read single record
    $query = "SELECT
					*
			  FROM
                " . $this->table_name . "
            WHERE
                MATCH_ID = ?";
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
    // bind id of product to be updated
    $stmt->bindParam(1, $this->match_id);
    // execute query
    $stmt->execute();
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // set values to object properties
    $this->match_id = $row['MATCH_ID'];
    $this->match_grp = $row['MATCH_GRP'];
    $this->match_code = $row['MATCH_CODE'];
    $this->match_t1_val = $row['MATCH_T1_VAL'];
    $this->match_t1_sco = $row['MATCH_T1_SCO'];
    $this->match_t1_tac = $row['MATCH_T1_TAC'];
    $this->match_t2_val = $row['MATCH_T2_VAL'];
    $this->match_t2_sco = $row['MATCH_T2_SCO'];
    $this->match_t2_tac = $row['MATCH_T2_TAC'];
    $this->match_score = $row['MATCH_SCORE'];
    
    return $this; 
	}

    function saveMatch(){
        // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
            MATCH_GRP = :MATCH_GRP,
            MATCH_CODE = :MATCH_CODE,
            MATCH_T1 = :MATCH_T1,
            MATCH_T2 = :MATCH_T2,
            MATCH_T1_TAC = :MATCH_T1_TAC,
            MATCH_T1_SCO = :MATCH_T1_SCO,
            MATCH_T1_VAL = :MATCH_T1_VAL,
            MATCH_T1_APT = :MATCH_T1_APT,
            MATCH_T1_BON = :MATCH_T1_BON,
            MATCH_T1_ATT_BON = :MATCH_T1_ATT_BON,
            MATCH_T1_MID_BON = :MATCH_T1_MID_BON,
            MATCH_T1_DEF_BON = :MATCH_T1_DEF_BON,
            MATCH_T2_TAC = :MATCH_T2_TAC,
            MATCH_T2_SCO = :MATCH_T2_SCO,
            MATCH_T2_VAL = :MATCH_T2_VAL,
            MATCH_T2_APT = :MATCH_T2_APT,
            MATCH_T2_BON = :MATCH_T2_BON,
            MATCH_T2_ATT_BON = :MATCH_T2_ATT_BON,
            MATCH_T2_MID_BON = :MATCH_T2_MID_BON,
            MATCH_T2_DEF_BON = :MATCH_T2_DEF_BON,
            MATCH_SCORE = :MATCH_SCORE,
            MATCH_WINNER = :MATCH_WINNER";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->MATCH_GRP=htmlspecialchars(strip_tags($this->MATCH_GRP));
    $this->MATCH_CODE=htmlspecialchars(strip_tags($this->MATCH_CODE));
    $this->MATCH_T1=htmlspecialchars(strip_tags($this->MATCH_T1));
    $this->MATCH_T2=htmlspecialchars(strip_tags($this->MATCH_T2));
    $this->MATCH_T1_TAC=htmlspecialchars(strip_tags($this->MATCH_T1_TAC));
    $this->MATCH_T1_SCO=htmlspecialchars(strip_tags($this->MATCH_T1_SCO));
    $this->MATCH_T1_VAL=htmlspecialchars(strip_tags($this->MATCH_T1_VAL));
    $this->MATCH_T1_APT=htmlspecialchars(strip_tags($this->MATCH_T1_APT));
    $this->MATCH_T1_BON=htmlspecialchars(strip_tags($this->MATCH_T1_BON));
    $this->MATCH_T1_ATT_BON=htmlspecialchars(strip_tags($this->MATCH_T1_ATT_BON));
    $this->MATCH_T1_MID_BON=htmlspecialchars(strip_tags($this->MATCH_T1_MID_BON));
    $this->MATCH_T1_DEF_BON=htmlspecialchars(strip_tags($this->MATCH_T1_DEF_BON));

    $this->MATCH_T2_TAC=htmlspecialchars(strip_tags($this->MATCH_T2_TAC));
    $this->MATCH_T2_SCO=htmlspecialchars(strip_tags($this->MATCH_T2_SCO));
    $this->MATCH_T2_VAL=htmlspecialchars(strip_tags($this->MATCH_T2_VAL));
    $this->MATCH_T2_APT=htmlspecialchars(strip_tags($this->MATCH_T2_APT));
    $this->MATCH_T2_BON=htmlspecialchars(strip_tags($this->MATCH_T2_BON));
    $this->MATCH_T2_ATT_BON=htmlspecialchars(strip_tags($this->MATCH_T2_ATT_BON));
    $this->MATCH_T2_MID_BON=htmlspecialchars(strip_tags($this->MATCH_T2_MID_BON));
    $this->MATCH_T2_DEF_BON=htmlspecialchars(strip_tags($this->MATCH_T2_DEF_BON));

    $this->MATCH_WINNER=htmlspecialchars(strip_tags($this->MATCH_WINNER));
    $this->MATCH_SCORE=htmlspecialchars(strip_tags($this->MATCH_SCORE));
    
    // bind values
    $stmt->bindParam(":MATCH_GRP", $this->MATCH_GRP);
    $stmt->bindParam(":MATCH_CODE", $this->MATCH_CODE);
    $stmt->bindParam(":MATCH_T1", $this->MATCH_T1);
    $stmt->bindParam(":MATCH_T2", $this->MATCH_T2);
    $stmt->bindParam(":MATCH_T1_TAC", $this->MATCH_T1_TAC);
    $stmt->bindParam(":MATCH_T1_SCO", $this->MATCH_T1_SCO);
    $stmt->bindParam(":MATCH_T1_VAL", $this->MATCH_T1_VAL);
    $stmt->bindParam(":MATCH_T1_APT", $this->MATCH_T1_APT);
    $stmt->bindParam(":MATCH_T1_BON", $this->MATCH_T1_BON);
    $stmt->bindParam(":MATCH_T1_ATT_BON", $this->MATCH_T1_ATT_BON);
    $stmt->bindParam(":MATCH_T1_MID_BON", $this->MATCH_T1_MID_BON);
    $stmt->bindParam(":MATCH_T1_DEF_BON", $this->MATCH_T1_DEF_BON);
    $stmt->bindParam(":MATCH_T2_TAC", $this->MATCH_T2_TAC);
    $stmt->bindParam(":MATCH_T2_SCO", $this->MATCH_T2_SCO);
    $stmt->bindParam(":MATCH_T2_VAL", $this->MATCH_T2_VAL);
    $stmt->bindParam(":MATCH_T2_APT", $this->MATCH_T2_APT);
    $stmt->bindParam(":MATCH_T2_BON", $this->MATCH_T2_BON);
    $stmt->bindParam(":MATCH_T2_ATT_BON", $this->MATCH_T2_ATT_BON);
    $stmt->bindParam(":MATCH_T2_MID_BON", $this->MATCH_T2_MID_BON);
    $stmt->bindParam(":MATCH_T2_DEF_BON", $this->MATCH_T2_DEF_BON);
    $stmt->bindParam(":MATCH_SCORE", $this->MATCH_SCORE);
    $stmt->bindParam(":MATCH_WINNER", $this->MATCH_WINNER);
    
 
    // execute query
    if($stmt->execute()){
        return true;
    }

    return false;
    }

    function updateMatch(){
        // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                MATCH_T1_SCO = :MATCH_T1_SCO,
                MATCH_T2_SCO = :MATCH_T2_SCO,
                MATCH_SCORE = :MATCH_SCORE
            WHERE
                MATCH_ID = :MATCH_ID";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->MATCH_SCORE=htmlspecialchars(strip_tags($this->MATCH_SCORE));
    $this->MATCH_T1_SCO=htmlspecialchars(strip_tags($this->MATCH_T1_SCO));
    $this->MATCH_T2_SCO=htmlspecialchars(strip_tags($this->MATCH_T2_SCO));
    $this->MATCH_ID=htmlspecialchars(strip_tags($this->MATCH_ID));
 
    // bind new values
    $stmt->bindParam(':MATCH_SCORE', $this->MATCH_SCORE);
    $stmt->bindParam(':MATCH_T1_SCO', $this->MATCH_T1_SCO);
    $stmt->bindParam(':MATCH_T2_SCO', $this->MATCH_T2_SCO);
    $stmt->bindParam(':MATCH_ID', $this->MATCH_ID);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
    }

}