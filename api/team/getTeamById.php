<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
 
// include database and object files
include_once '../config/database.php';
include_once '../models/Team.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$team = new Team($db);
 
// set ID property of product to be edited
$team->team_id = isset($_GET['team_id']) ? $_GET['team_id'] : die();
 
// read the details of product to be edited
$team->getTeamById();

$team_arr = array("team_id" => $team->team_id,
"team_name"=>$team->team_name,
"team_code"=>$team->team_code,
"team_flag"=>$team->team_flag);

print_r(json_encode($team_arr));


