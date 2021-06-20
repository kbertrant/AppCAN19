<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../models/Group.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$group = new Group($db);
 
// query products
$stmt = $group->getAllGroup();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){

    // products array
    $group_arr=array();
    $group_arr["groups"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $group_item=array(
            "id" => $id,
            "group_name" => $group_name);
 
        array_push($group_arr["groups"], $group_item);
    }
 
    echo json_encode($group_arr);
}
 
else{
    echo json_encode(
        array("message" => "No group found.")
    );
}