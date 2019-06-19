<?php

class Message implements JsonSerializable {
    private $message;
    private $user;
    private $time;

    function __get($name){
        return $this->$name;
    }
    function __set($name, $value){
        $this->$name = $value;
    }

    function jsonSerialize(){
        return get_object_vars($this);
    }
}