<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
 
// include database and object files
include_once '../config/database.php';
include_once '../models/Tactic.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$tactic = new Tactic($db);
 
// set ID property of product to be edited
$tactic->tac_id = isset($_GET['tac_id']) ? $_GET['tac_id'] : die();
 
// read the details of product to be edited
$tactic->getTacticById();

if($tactic->tac_name!=null){

$tactic_arr = array("tac_id"=>$tactic->tac_id,
"tac_name"=>$tactic->tac_name,
"tac_code"=>$tactic->tac_code,
"tac_bon"=>$tactic->tac_bon,
"tac_opp"=>$tactic->tac_opp,
"tac_apt"=>$tactic->tac_apt);

echo json_encode($tactic_arr);
// set response code - 200 OK
    http_response_code(200);
}
else{

	http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "tactic does not exist."));
	

}
