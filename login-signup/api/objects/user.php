<?php
class User{

    // database connection and table name
    private $conn;
    private $db_table = "users";

    // object properties
    public $id;
    public $username;
    public $password;
    public $created;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // user signup
    function signup(){
      if ($this->ifAlreadyExist()) {
        //if user already exists exit function
        return false;
      }

      //insert new user record via query statement
      $sql = "INSERT INTO" . $this->db_table . "SET username=:username, pasword=:password, created=:created";

      //prepare
      $stmt = $this->conn->prepare($sql);

      //cleaning, sanitize variables before binding
      //conv characters to html entities and remove tags - help prevent XSS
      $this->username=htmlspecialchars(strip_tags($this->username));
      //hashing password
      $this->password=htmlspecialchars(strip_tags($this->password));
      $this->created=htmlspecialchars(strip_tags($this->created));

      //after sanitizing, bind values
      $stmt->bindParam(":username", $this->username);
      $stmt->bindParam(":password", $this->password);
      $stmt->bindParam(":created", $this->created);

      //finally, execute the statement
      if ($stmt->execute()) {
        //if statement executes, use a PDO function to set ID to the last id inserted to db
        $this->id = $this->conn->lastInsertId();
        return true;
      }

      return false;

    }

    // user login
    function login(){
      //if username and password exist in DB, select the following values
      $sql = "SELECT `id`, `username`, `password`, `created` FROM" . $this->db_table . "WHERE username='".$this->username."' AND password='".$this->password."'";

      //prepare
      $stmt = $this->conn->prepare($sql);

      //execute
      $stmt->execute();
      return $stmt;
    }

    // function to check if user already exists
    function ifAlreadyExist(){
      //checking db if username already exists in db
      $sql = "SELECT * FROM" . $this->db_table . "WHERE username='". $this->username."'";

      //prepare
      $stmt = $this->conn->prepare($sql);

      //execute
      $stmt->execute();

      //if there is more than 0 results then a user already exists, return TRUE
      if ($stmt->rowCount() > 0) {
        return true;
      }
      else {
        return false;
      }
    }
}
