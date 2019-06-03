<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

if ($_POST['logout'] == "logout") {
    unset($_SESSION['username']);
    header("Location: index.php");
}

?>