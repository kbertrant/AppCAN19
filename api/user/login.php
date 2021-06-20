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
include_once '../models/User.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 

// set product property values
if(!empty($data->user_name) && !empty($data->user_phone) && !empty($data->user_email) && !empty($data->password) && !empty($data->user_city) && !empty($data->user_age) && !empty($data->user_profession) && !empty($data->user_sport) && !empty($data->user_hobby)){
$user->user_name = $data->user_name;
$user->user_phone = $data->user_phone;
$user->user_email = $data->user_email;
$user->password = $data->password;
$user->user_city = $data->user_city;
$user->user_age = $data->user_age;
$user->user_profession = $data->user_profession;
$user->user_sport = $data->user_sport;
$user->user_hobby = $data->user_hobby;

// create the product
if($user->registration()){
    // set response code - 201 created
        http_response_code(201);

        echo json_encode(array("message" => "user was created."));
}
else{
    // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(array("message" => "Unable to create User."));
}
}
else{
	 // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create User. Data is incomplete."));
}