<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
 
// include database and object files
include_once '../config/database.php';
include_once '../models/Group.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$group = new Group($db);
 
// set ID property of product to be edited
$group->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of product to be edited
$group->getGroupById();

if($group->group_name!=null){

$group_arr = array("id"=>$group->id,
"group_name"=>$group->group_name);

echo json_encode($group_arr);
// set response code - 200 OK
    http_response_code(200);
}
else{

	http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "group does not exist."));
	

}