<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../models/Player.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$player = new Player($db);
 
// query products
$stmt = $player->getAllPlayer();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){

    // products array
    $player_arr=array();
    $player_arr["players"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $player_item=array(
            "ply_id" => $PLY_ID,
            "team_id" => $TEAM_ID,
            "line_id" => $LINE_ID,
            "ply_name" => $PLY_NAME,
            "ply_nbr" => $PLY_NBR,
            "ply_gkp_val" => $PLY_GKP_VAL,
            "ply_def_val" => $PLY_DEF_VAL,
            "ply_mid_val" => $PLY_MID_VAL,
            "ply_att_val" => $PLY_ATT_VAL,
            "ply_val" => $PLY_VAL);
 
        array_push($player_arr["players"], $player_item);
    }
 
    echo json_encode($player_arr);
}
 
else{
    echo json_encode(
        array("message" => "No player found.")
    );
}