<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
 
// include database and object files
include_once '../config/database.php';
include_once '../models/Player_Line.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$pp = new Player_Line($db);
 
// set ID property of product to be edited
$pp->line_id = isset($_GET['line_id']) ? $_GET['line_id'] : die();
 
// read the details of product to be edited
$pp->getPositionById();

if($pp->line_code!=null){

$pp_arr = array("line_id"=>$pp->line_id,
				"line_code"=>$pp->line_code);

echo json_encode($pp_arr);
// set response code - 200 OK
    http_response_code(200);
}
else{

	http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "position does not exist."));
	

}