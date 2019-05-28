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

//getting UN/PW from input and setting them in variables
//shorthand if statement. if UN/PW is set then set variable accordingly, else die
$user->username = isset($_GET['username']) ? $_GET['username'] : die();
$user->password = isset($_GET['password']) ? $_GET['password'] : die();

//attempt log in
$stmt = $user->login();

//dealing with statement results
if ($stmt->rowCount() > 0) {
  $row = $stmt->fetch(PDO::fetch_assoc);

  //creating an array to hold login outcome
  $user_login=array(
    "status" => true,
    "message" => "Login was succesfull woohoooo",
    "id" => $row['id'],
    "username" => $row['username']
  );
}
else {
  //if login fails....
  $user_login=array(
    "status" => false,
    "message" => "Login failed, give it another try",
  );
}

//as always print results in json format
print_r(json_encode($user_login));

?>
