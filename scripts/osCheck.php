<?php

//Checks to see if host OS is Ubuntu
if (!fnmatch("*Ubuntu*", php_uname('v'))) {
    $err = new fatalError;
    $err->displayError("This is running on an unsupported OS. Currently this only supports Ubuntu.");
}

//These Verions of Ubuntu are supported
$supported_versions = array("16.04", "18.04", "19.04");

//values to replace lsb_release command
$replacement = array("Release:", " ");

//Command to get version num of server
$server_version_num = shell_exec('lsb_release -r');
$server_version_num = str_replace($replacement, " ", $server_version_num);

//Counter to determine unsupported version
$match_counter = 0;

foreach ($supported_versions as $key => $version_num) {
    if (fnmatch("*".$version_num."*", $server_version_num)) {
        $match_counter = $match_counter + 1;    
    }
}

//Operation terminates if unsupported version of Ubuntu is detected
if ($match_counter == 0) {
    $err = new fatalError;
    $err->displayError("This is an unsupported version of Ubuntu. Only 16.04, 18.04, and 19.04 are supported.");
}

?>