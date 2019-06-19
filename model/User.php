<?php

class User implements JsonSerializable {
    private $username;
    private $age;

    function __get($name){
        return $this->$name;
    }
    function __set($name, $value){
        $this->$name = $value;
    }

    public function jsonSerialize(){
        return get_object_vars($this);
    }

}