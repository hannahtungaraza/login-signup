<?php
//file for db connections
include_once '../config/database.php';

//instantiating the user object
include_once '../objects/user.php';

//new db object
$database = new Database();
$db = $database->getConnection();

//new user object
$user = new User($db);

//setting user variable values from POST data
$user->username = $_POST['username'];
$user->password = $_POST['password'];
//created will be the timestamp of signup
$user->created = date('Y-m-d H:i:s');

//creating the users
if ($user->signup()) {
  //creating an array to hold signup outcome
  $user_signup=array(
    "status" => true,
    "message" => "Signup was succesfull woohoooo",
    "id" => $user->id,
    "username" => $user->username
  );
}
else {
  // code...
  $user_signup=array(
    "status" => false,
    "message" => "Username already exists...have you been here before?"
  );
}
//printing the array via json encode function
print_r(json_encode($user_signup));
?>
