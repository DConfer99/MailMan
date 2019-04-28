<?php

//Runs command to see if postfix is installed
$postfix_check = shell_exec("which postfix");

//Stops operation if postfix is not installed
if ($postfix_check == "") {
    $err = new fatalError;
    $err->displayError("Postfix is not installed on this server. Please install using the command: \n <i>DEBIAN_FRONTEND=noninteractive apt-get install postfix</i> ");
}

//TODO:
//!: Add Dovecot Support!!!
?>