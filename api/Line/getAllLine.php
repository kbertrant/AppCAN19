<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../models/Player_Line.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$team = new Player_Line($db);
 
// query products
$stmt = $team->getAllLine();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){

    // products array
    $team_arr=array();
    $team_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $team_item=array(
            "line_id" => $LINE_ID,
            "line_code" => $LINE_CODE
        );
 
        array_push($team_arr["records"], $team_item);
    }
 
    echo json_encode($team_arr);
}
 
else{
    echo json_encode(
        array("message" => "No products found.")
    );
}