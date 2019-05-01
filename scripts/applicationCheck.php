<?php

//Runs command to see if postfix is installed
$postfix_check = shell_exec("which postfix");

//Stops operation if postfix is not installed
if ($postfix_check == "") {
    $err = new fatalError;
    $err->displayError("Postfix is not installed on this server. Please install using the command: \n <i>DEBIAN_FRONTEND=noninteractive apt-get install postfix</i> ");
}

//Looks to see the exsistance and permissions of /etc/postfix/main.cf
$postfix_config_check = shell_exec("ls -l /etc/postfix/main.cf");

//Halts operationif /etc/postfix/main.cf does not exsist
if ($postfix_config_check == "") {
    $err = new fatalError;
    $err->displayError("Postfix config file does not exsist. Please execute the command: <i>sudo touch /etc/postfix/main.cf</i>");
}

//Looks to see if the correct permissions are set on the file
if (!fnmatch("-rw-r--r--*", $postfix_config_check)) {
    $err = new fatalError;
    $err->displayError("Permissions on /etc/postfix/main.cf are not correct. Please change them by running: <i>sudo chmod 622 /etc/postfix/main.cf</i>");
}

//Looks to see if www-data is the owner of /etc/postfix/main.cf
if (!fnmatch("*www-data root*", $postfix_config_check)) {
    $err = new fatalError;
    $err->displayError("www-data is not the owner of /etc/postfix/main.cf. Please change it by running: <i>sudo chown www-data:root /etc/postfix/main.cf</i>");
}


//TODO:
//1: Add Dovecot Support!!!
?>
