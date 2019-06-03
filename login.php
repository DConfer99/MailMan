<?php
session_start();

if ($_POST['submit'] == "submit") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new SQLite3($_SERVER['DOCUMENT_ROOT'].'/db/mailman.db');
    $result = $db->query('SELECT "password" FROM "users" WHERE "username" = "'. $username .'"');
    $hash = $result->fetchArray();
    

    //$salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
    //$hash = crypt($password, '$2y$12$' . $salt);

    if ($hash[0] == crypt($password, $hash[0])) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
    }
}

?>

<?php include "header.php"; ?>
<link href="css/login.css" rel="stylesheet">

<div class="container h-80">
<div class="row align-items-center h-100">
    <div class="col-3 mx-auto">
        <div class="text-center">
            <img id="profile-img" class="rounded-circle profile-img-card" src="img/MailMan_Logo.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form  class="form-signin" method="post" action="">
                
                <input type="text" name="username" class="form-control form-group" placeholder="Username">
                <input type="password" name="password" id="inputPassword" class="form-control form-group" placeholder="Password" required autofocus>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="submit" value="submit">Login</button>
            </form><!-- /form -->
        </div>
    </div>
</div>
</div>