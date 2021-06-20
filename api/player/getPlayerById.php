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
$player->ply_id = isset($_GET['ply_id']) ? $_GET['ply_id'] : die();
 
// read the details of product to be edited
$player->getPlayerById();

if($player->ply_name!=null){

$player_arr = array("ply_id"=>$player->ply_id,
"team_id"=>$player->team_id,
"line_id"=>$player->line_id,
"ply_name"=>$player->ply_name,
"ply_nbr"=>$player->ply_nbr,
"ply_gkp_val"=>$player->ply_gkp_val,
"ply_def_val"=>$player->ply_def_val,
"ply_mid_val"=>$player->ply_mid_val,
"ply_att_val"=>$player->ply_att_val,
"ply_val"=>$player->ply_val);

echo json_encode($player_arr);
// set response code - 200 OK
    http_response_code(200);
}
else{

	http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "player does not exist."));
	

}


