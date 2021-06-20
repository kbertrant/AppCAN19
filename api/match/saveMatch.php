<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../models/Match.php';
 
$database = new Database();
$db = $database->getConnection();
 
$product = new Match($db);
 
// get posted data
$data = (object)$_POST;
//$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->MATCH_CODE) &&
    !empty($data->MATCH_GRP) &&
    !empty($data->MATCH_T2) &&
    !empty($data->MATCH_T1) &&
    !empty($data->MATCH_T1_TAC) &&
    !empty($data->MATCH_T1_SCO) &&
    !empty($data->MATCH_T1_APT) &&
    !empty($data->MATCH_T1_BON) &&
    !empty($data->MATCH_T1_VAL) &&
    !empty($data->MATCH_T1_ATT_BON) &&
    !empty($data->MATCH_T1_MID_BON) &&
    !empty($data->MATCH_T1_DEF_BON) &&
    !empty($data->MATCH_T2_TAC) &&
    !empty($data->MATCH_T2_SCO) &&
    !empty($data->MATCH_T2_APT) &&
    !empty($data->MATCH_T2_BON) &&
    !empty($data->MATCH_T2_VAL) &&
    !empty($data->MATCH_T2_ATT_BON) &&
    !empty($data->MATCH_T2_MID_BON) &&
    !empty($data->MATCH_T2_DEF_BON) &&
    !empty($data->MATCH_WINNER) &&
    !empty($data->MATCH_SCORE)
){
 
 var_dump($data);

    // set product property values
    $product->MATCH_CODE = $data->MATCH_CODE;
    $product->MATCH_GRP = $data->MATCH_GRP;
    $product->MATCH_T2 = $data->MATCH_T2;
    $product->MATCH_T1 = $data->MATCH_T1;
    $product->MATCH_T1_TAC = $data->MATCH_T1_TAC;
    $product->MATCH_T1_SCO = $data->MATCH_T1_SCO;
    $product->MATCH_T1_APT = $data->MATCH_T1_APT;
    $product->MATCH_T1_BON = $data->MATCH_T1_BON;
    $product->MATCH_T1_VAL = $data->MATCH_T1_VAL;
    $product->MATCH_T1_ATT_BON = $data->MATCH_T1_ATT_BON;
    $product->MATCH_T1_MID_BON = $data->MATCH_T1_MID_BON;
    $product->MATCH_T1_DEF_BON = $data->MATCH_T1_DEF_BON;
    $product->MATCH_T2_TAC = $data->MATCH_T2_TAC;
    $product->MATCH_T2_SCO = $data->MATCH_T2_SCO;
    $product->MATCH_T2_APT = $data->MATCH_T2_APT;
    $product->MATCH_T2_BON = $data->MATCH_T2_BON;
    $product->MATCH_T2_VAL = $data->MATCH_T2_VAL;
    $product->MATCH_T2_ATT_BON = $data->MATCH_T2_ATT_BON;
    $product->MATCH_T2_MID_BON = $data->MATCH_T2_MID_BON;
    $product->MATCH_T2_DEF_BON = $data->MATCH_T2_DEF_BON;
    $product->MATCH_SCORE = $data->MATCH_SCORE;
    $product->MATCH_WINNER = $data->MATCH_WINNER;
 
    // create the product
    if($product->saveMatch()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Match was created."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create a match."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create match. Data is incomplete."));
}