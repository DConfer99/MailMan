<?php

if (!fnmatch("*Ubuntu*", php_uname('v'))) {
    $err = new fatalError;
    $err->displayError("This is running on an unsupported OS. Currently this only supports Ubuntu.");
}

?>