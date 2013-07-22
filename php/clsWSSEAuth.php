<?php

class clsWSSEAuth {
private $Username;
private $Password;
function __construct($username, $password) {
    $this->Username=$username;
    $this->Password=$password;
    }
}


class clsWSSEToken {
private $UsernameToken;

function __construct ($innerVal){
    $this->UsernameToken = $innerVal;
    }
}
?>
