<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
 
// include database and object files
include_once '../config/database.php';
include_once '../models/Player_Sta.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$player = new Player_Sta($db);
 
// set ID property of product to be edited
$player->ply_id_sta = isset($_GET['ply_id_sta']) ? $_GET['ply_id_sta'] : die();
 
// read the details of product to be edited
$player->getPlayerStaById();

if($player->ply_id_sta!=null){

$player_arr = array("ply_id_sta"=>$player->ply_id_sta,
"ply_id"=>$player->ply_id,
"ply_tit"=>$player->ply_tit,
"ply_sub"=>$player->ply_sub,
"ply_shp"=>$player->ply_shp,
"ply_inj"=>$player->ply_inj,
"ply_crd"=>$player->ply_crd,
"ply_dsq"=>$player->ply_dsq,
"ply_sco"=>$player->ply_sco,
"ply_ass"=>$player->ply_ass);

echo json_encode($player_arr);
// set response code - 200 OK
    http_response_code(200);
}
else{

	http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "player stats is not available."));
	

}


