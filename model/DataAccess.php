<?php

//MONGODB
// $connection = new Mongo();

class DataAccess {
    private static $instance = null;
    private $connection;

    private function __construct() {
        require_once "db/database.php";
        $this->connection = new PDO("mysql:host={$host}; dbname={$database}", $user, $contrasena, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new DataAccess();
        }
        return self::$instance;
    }

    public function getConnection(){
        return $this->connection;
    }

    function registerUser($username, $age){
        $connection = $this->getConnection();
        $statement = $connection->prepare("INSERT INTO User (username, age) VALUES (:username, :age)");
        $statement->bindValue(":username", $username);
        $statement->bindValue(":age", $age);
        $statement->execute();
        return $connection->lastInsertId();
    }

    function registerMessage($userid, $message, $time){
        $connection = $this->getConnection();
        $statement = $connection->prepare("INSERT INTO Message (user, message, time) VALUES(?, ?, ?)");
        $statement->execute([$userid, $message, $time]);
    }

    function getMessages(){
        $connection = $this->getConnection();
        $statement = $connection->prepare("SELECT * FROM Message");
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_CLASS, "Message");
        foreach($results as $result){
            $statement = $connection->prepare("SELECT username FROM User WHERE id = :user");
            $statement->bindValue(":user", $result->user);
            $statement->execute();
            $res = $statement->fetch(PDO::FETCH_OBJ);
            $result->user = $res->username;
        }
        return $results;
    }

}