<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
 
// include database and object files
include_once '../config/database.php';
include_once '../models/Player.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$player = new Player($db);
 
// set ID property of product to be edited
$player->team_id = isset($_GET['team_id']) ? $_GET['team_id'] : die();
 
// read the details of product to be edited
$stmt = $player->getAllpower();

//$num = $stmt->rowCount();
//var_dump($stmt);
if($stmt>0){

//$player_arr = array("pla_value"=>$stmt);

echo json_encode($stmt);
// set response code - 200 OK
    http_response_code(200);
}
else{

	http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "player does not exist."));
	

}