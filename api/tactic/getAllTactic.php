<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../models/Tactic.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$tactic = new Tactic($db);
 
// query products
$stmt = $tactic->getAllTactic();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){

    // products array
    $tactic_arr=array();
    $tactic_arr["tactics"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $tactic_item=array(
            "tac_id" => $TAC_ID,
            "tac_name" => $TAC_NAME,
            "tac_code" => $TAC_CODE,
            "tac_bon" => $TAC_BON,
            "tac_opp"=> $TAC_OPP,
            "tac_apt" => $TAC_APT);
 
        array_push($tactic_arr["tactics"], $tactic_item);
    }
 
    echo json_encode($tactic_arr);
}
 
else{
    echo json_encode(
        array("message" => "No Tactic found.")
    );
}