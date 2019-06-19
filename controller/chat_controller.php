<?php

session_start();

require_once "../model/User.php";
require_once "../model/Message.php";
require_once "../model/DataAccess.php";

if(isset($_REQUEST["username"])){
    $_SESSION["username"] = $_REQUEST["username"];
    $_SESSION["age"] = $_REQUEST["age"];
    $_SESSION["id"]  = DataAccess::getInstance()->registerUser($_SESSION["username"], $_SESSION["age"]);
    $messages = DataAccess::getInstance()->getMessages();
}

    if(isset($_REQUEST["msg"])){
        $msg = $_REQUEST["msg"];
        $time = date("h:i");
        DataAccess::getInstance()->registerMessage($_SESSION["id"], $msg, $time);
        $messages = DataAccess::getInstance()->getMessages();
    }

if(isset($_REQUEST["update"])){
    header('Content-Type: application/json');
    $messages = DataAccess::getInstance()->getMessages();
    echo json_encode($messages);
    exit();
}

require_once "../view/chat.php";