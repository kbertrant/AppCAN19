<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
 
// include database and object files
include_once '../config/database.php';
include_once '../models/Team_Sta.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$player = new Team_Sta($db);
 
// set ID property of product to be edited
$player->TEAM_STA_ID = isset($_GET['TEAM_STA_ID']) ? $_GET['TEAM_STA_ID'] : die();
 
// read the details of product to be edited
$player->getTeamStaById();

if($player->TEAM_STA_ID!=null){

$player_arr = array("TEAM_STA_ID"=>$player->TEAM_STA_ID,
"team_id"=>$player->team_id,
"rnk_id"=>$player->rnk_id,
"grp_id"=>$player->grp_id,
"team_win"=>$player->team_win,
"team_los"=>$player->team_los,
"team_draw"=>$player->team_draw,
"team_pts"=>$player->team_pts,
"team_sco"=>$player->team_sco,
"team_con"=>$player->team_con,
"team_avg"=>$player->team_avg);

echo json_encode($player_arr);
// set response code - 200 OK
    http_response_code(200);
}
else{

	http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "player stats is not available."));
	

}


