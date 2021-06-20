<?php
/**
 *
 */
class User {

    private $conn;
    private $table_name = "t_user";

    // object properties
    public $id;
    public $team_id;
    public $user_name;
    public $user_phone;
    public $user_email;
    public $password;
    public $user_city;
    public $user_age;
    public $user_profession;
    public $user_sport;
    public $user_hobby;
    public $user_win;
    public $user_draw;
    public $user_lose;
    public $user_goal_scored;
    public $user_goal_conceded;
    public $user_goal_average;
    public $user_last_team;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function registration(){
    	// query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                user_name=:user_name, user_phone=:user_phone,
                user_email=:user_email, password=:password,
                user_city=:user_city,user_age=:user_age,
                user_profession=:user_profession, user_sport=:user_sport,
                user_hobby=:user_hobby";

    // prepare query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->user_name=htmlspecialchars(strip_tags($this->user_name));
    $this->user_phone=htmlspecialchars(strip_tags($this->user_phone));
    $this->user_email=htmlspecialchars(strip_tags($this->user_email));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->user_city=htmlspecialchars(strip_tags($this->user_city));
	$this->user_age=htmlspecialchars(strip_tags($this->user_age));
    $this->user_profession=htmlspecialchars(strip_tags($this->user_profession));
    $this->user_sport=htmlspecialchars(strip_tags($this->user_sport));
    $this->user_hobby=htmlspecialchars(strip_tags($this->user_hobby));

    // bind values
    $stmt->bindParam(":user_name", $this->user_name);
    $stmt->bindParam(":user_phone", $this->user_phone);
    $stmt->bindParam(":user_email", $this->user_email);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":user_city", $this->user_city);
    $stmt->bindParam(":user_age", $this->user_age);
    $stmt->bindParam(":user_profession", $this->user_profession);
    $stmt->bindParam(":user_sport", $this->user_sport);
    $stmt->bindParam(":user_hobby", $this->user_hobby);

    // execute query
    if($stmt->execute()){
        return true;
    }

    return false;
    }


    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {

       // $stmt = $this->conn->prepare("SELECT * FROM costumers WHERE email_address = 'email'");
        $query = ("SELECT * FROM t_user WHERE email_address = ?");
             // prepare query statement
        $stmt = $this->conn->prepare( $query );
        // bind id of product to be updated
        $stmt->bindParam(1, $this->user_email);
        // execute query
        $stmt->execute();
         // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rows > 0) {
            $re = $row['password'];
            if ($re == $password) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {

        // $stmt = $this->conn->prepare("SELECT * FROM costumers WHERE email_address = 'email'");
        $query = ("SELECT * FROM t_user WHERE user_email = ?");
             // prepare query statement
        $stmt = $this->conn->prepare( $query );
        // bind id of product to be updated
        $stmt->bindParam(1, $this->user_email);
        // execute query
        $stmt->execute();
         // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($rows > 0) {
            // user existed
            return true;
        } else {
            // user not existed
            return false;
        }
    }


    function login(){}

    function logout(){}
}
