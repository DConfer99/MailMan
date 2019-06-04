<?php
//Things to implement
//
//Hostname
//Imap or pop3
//Admin User

$hostname = shell_exec("hostname");
$hostname = trim($hostname);


if(isset($_POST['submit'])){
    $rootPassword = $_POST['rootPassword'];

    $rootExec = new rootExec;

    //installs pop3 or imap
    if ($_POST['pop3'] != "" || $_POST['imap'] != "") {
        $rootExec->command("apt install -y " . $_POST['pop3'] . " " . $_POST['imap'], $rootPassword);
        if ($_POST['pop3'] != "") {
            $pop3 = "pop3 ";
        } else {
            $pop3 = "";
        }
        if ($_POST['imap'] != "") {
            $imap = "imap";
        } else {
            $imap = "";
        }

        $rootExec->command("printf 'protocols = " . $pop3 . $imap . "' >> /etc/dovecot/dovecot.d");
    }

    //creates postfix main configuration file
    /*
    if (!file_exists("/etc/postfix/main.cf")){
        $rootExec->command("touch /etc/postfix/main.cf", $rootPassword);
    }

    $myhostname = "\$myhostname";
    $mail_name = "\$mail_name";

    //configures main configuration file- COME BACK AND FIX THIS STUPID ERROR
    $rootExec->command($main_config, $rootPassword);
    */

    //Creating Admin User
    $admin_username = $_POST['admin_username'];
    $admin_password = $_POST['admin_password'];

    $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
    $hash = crypt($admin_password , '$2y$12$' . $salt);

    //Creates the database file 
    $db = new SQLite3($_SERVER['DOCUMENT_ROOT'].'/db/mailman.db');

    //Creates the user table in the database
    $db->query('CREATE TABLE IF NOT EXISTS "users" (
        "user_id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        "username" TEXT UNIQUE NOT NULL,
        "password" TEXT NOT NULL
    )');

    //Creates an permissions table in the database
    /*
    $db->query('CREATE TABLE IF NOT EXISTS "permissions" (
        "user_id" INTEGER NOT NULL,
        "create_user" 
    )');*/
    

    //Inserting the admin user into the database
    $db->exec('BEGIN');
    $db->query('INSERT INTO "users" ("username", "password")
        VALUES ("'. $admin_username .'", "'. $hash .'")');
    $db->exec('COMMIT');
}

$db_check = shell_exec("ls ".$_SERVER["DOCUMENT_ROOT"]."/db/mailman.db");



//Checks to see if settings database exists
if ($db_check == "") {
    //Sets initial values for the form
    if (!isset($_POST)) {
        
        
    } else {
        $postfix_version = shell_exec("postconf mail_version | cut -c 16-");
        $apache_version = shell_exec("apache2 -v | grep \"Server version:\" | cut -c 17-");
    }
//Looks to DNS server to get public IP
$pub_ip_add = shell_exec("dig +short myip.opendns.com @resolver1.opendns.com");
$pub_ip_add = trim($pub_ip_add);

$opendkim_key_1 = shell_exec("cat /etc/opendkim/keys/$hostname/default.txt | grep 'k='");
explode('(', $opendkim_key_1);

$opendkim_key_2 = shell_exec("cat /etc/opendkim/keys/$hostname/default.txt | grep 'p='");

$opendkim_key_3 = shell_exec("cat /etc/opendkim/keys/$hostname/default.txt | grep 'key default'");
explode(')', $opendkim_key_3);
?>

<?php require "header.php"; ?>

<body>
        
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="../img/logo-text.png" width="113" height="30" alt="">
        </a>
    </nav>

    <div class="container">
        <!-- External toolbar sample-->
        <div class="row d-flex align-items-center p-3 my-3 text-white-50">
            <div class="col-12 col-lg-6 col-sm-12">
              <label style="display: none">Theme:</label>
              <select id="theme_selector" class="custom-select col-lg-6 col-sm-12" style="display: none">
                    <option value="circles">circles</option>
                    <option value="arrows">arrows</option>
                    <option value="default">default</option>
                    <option value="dots">dots</option>
              </select>
            </div>
            <div class="col-12 col-lg-6 col-sm-12">
              <label style="display:none;">External Buttons:</label>
              <div class="btn-group col-lg-6 col-sm-12" role="group" style="display:none;">
                  <button class="btn btn-secondary" id="prev-btn" type="button">Go Previous</button>
                  <button class="btn btn-secondary" id="next-btn" type="button">Go Next</button>
                  <button class="btn btn-danger" id="reset-btn" type="button">Reset Wizard</button>
              </div>
            </div>
        </div>

        <!-- SmartWizard html -->
        <div id="smartwizard">
            <ul style="margin-bottom: 80px;">
                <li style="margin-bottom: 40px;"><a href="#step-0">Welcome<br /><small>Welcome Screen</small></a></li>
                <li style="margin-bottom: 40px;"><a href="#step-1">Step 1<br /><small>Dovecot Method</small></a></li>
                <li style="margin-bottom: 40px;"><a href="#step-2">Step 2<br /><small>Register MailMan Administrator</small></a></li>
                <li style="margin-bottom: 40px;"><a href="#step-3">Step 3<br /><small>DNS Information</small></a></li>
                <li style="margin-bottom: 40px;"><a href="#step-4">Step 4<br /><small>Confirm Changes</small></a></li>
            </ul>

            <div>
                <form method="post" action="">
                    <div id="step-0" class="">
                        <h3 class="border-bottom border-gray pb-2">Welcome to MailMan</h3>
                        Welcome to MailMan. The ultimate mail server monitoring untility! This initial setup will step you through the process of setting up your mail server. Please note that <b>you need access to your domain DNS settings and have the password for the root user on this server in order for this software to work!</b>
                    </div>
                    <div id="step-1" class="" style="display: none;">
                        <h3 class="border-bottom border-gray pb-2">Dovecot Method</h3>
                        <div>
                            Select email access method for Dovecot:<br>
                            <input type="checkbox" name="imap" value="dovecot-imapd">IMAP<br>
                            <input type="checkbox" name="pop3" value="dovecot-pop3d">POP3
                            <small id="hostnameHelpBlock" class="form-text text-muted">
                                This is the method by which users will be able to access their emails. IMAP allows users to simply view emails from the server directly, while POP3 requires users to download new emails before viewing them. We reccommend IMAP if you are unsure.
                            </small>
                        </div>
                    </div>
                    <div id="step-2" class="" style="display: none;">
                        <h3 class="border-bottom border-gray pb-2">Register MailMan Administrator</h3>
                        <div class="card">
                            <div class="card-header">Admin User for the MailMan Interface</div>
                            <div class="card-block p-0">
                            <table class="table">
                                <tbody>
                                    <tr> <th>Username:</th> <td><input type="text" name="admin_username"></td> </tr>
                                    <tr> <th>Password:</th> <td><input type="password" name="admin_password"></td> </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div id="step-3" class="" style="display: none;">
                        <h3 class="border-bottom border-gray pb-2">DNS Information</h3>
                        <label for="certbotEmail">Email for Notifications</label>
                        <input type="text" class="form-control" id="certbotEmail" name="certbotEmail" placeholder="you@youremail.com" onkeyup="myEmail();"><br>
                        <div class="card">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td>Type</td>
                                        <td>TTL</td>
                                        <td>Data</td>
                                    </tr>
                                    <tr>
                                        <td id="dns_host"><?php echo $hostname; ?></td>
                                        <td>A</td>
                                        <td>1h</td>
                                        <td><?php echo $pub_ip_add; ?></td>
                                    </tr>
                                    <tr>
                                        <td id="dns_host"><?php echo $hostname; ?></td>
                                        <td>MX</td>
                                        <td>1h</td>
                                        <td>10 <?php echo $hostname; ?></td>
                                    </tr>
                                    <tr>
                                        <td id="dns_host"><?php echo $hostname; ?></td>
                                        <td>TXT</td>
                                        <td>1h</td>
                                        <td>"v=spf1 ip4:<?php echo $pub_ip_add; ?> mx -all"</td>
                                    </tr>
                                    <tr>
                                        <td id="dns_host">_dmarc.<?php echo $hostname; ?></td>
                                        <td>TXT</td>
                                        <td>1h</td>
                                        <td>"v=DMARC1; p=reject; pct=100; fo=1; <br> rua=mailto:<span id="dnsEmail"><span> "</td>
                                    </tr>
                                    <tr>
                                        <td id="dns_host">default_domainkey.<?php echo $hostname; ?></td>
                                        <td>TXT</td>
                                        <td>1h</td>
                                        <td>"<?php echo $opendkim_key_1[1] . "\" \"" . $opendkim_key_2 . "\" \"" . $opendkim_key_3[0]; ?> "</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><br>
                        <small id="hostnameHelpBlock" class="form-text text-muted">
                            Enter the following information in your DNS records. <b style="color: red;">Note that this process may take up to an hour to take effect and must be applied before continuing!</b> <br>
                        </small><br>
                    </div>
                    <div id="step-4" class="" style="display: none;">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rootPasswordModal">
                            Apply Changes
                        </button>
                    </div>
                    <div class="modal fade" id="rootPasswordModal" tabindex="-1" role="dialog" aria-labelledby="rootPasswordModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rootPasswordModalLabel">Root Privileges Required</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    This action requires root privileges on this server in order to execute. Please enter the password for the root user.<br>This command may take a couple of minutes to execute.<br><br>
                                    <input type="password" class="form-control" id="rootPassword" name="rootPassword" placeholder="Root Password">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>

    <!-- Include jQuery -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous"></script>

    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Include SmartWizard JavaScript source -->
    <script type="text/javascript" src="../js/jquery.smartWizard.min.js"></script>

    <script type="text/javascript">

        function myEmail() {
        var x = document.getElementById("certbotEmail");
        document.getElementById("dnsEmail").innerHTML = x.value.concat(" \"");
        //document.getElementById("message_host").innerHTML = x.value;
        }

        $(document).ready(function(){

            // Step show event
            $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
               //alert("You are on step "+stepNumber+" now");
               if(stepPosition === 'first'){
                   $("#prev-btn").addClass('disabled');
               }else if(stepPosition === 'final'){
                   $("#next-btn").addClass('disabled');
               }else{
                   $("#prev-btn").removeClass('disabled');
                   $("#next-btn").removeClass('disabled');
               }
            });

            // Toolbar extra buttons
            
            var btnFinish = $('<button></button>').text('Finish')
                                             .addClass('btn btn-info')
                                             .on('click', function(){ alert('Finish Clicked'); });
            var btnCancel = $('<button></button>').text('Cancel')
                                             .addClass('btn btn-danger')
                                             .on('click', function(){ $('#smartwizard').smartWizard("reset"); });
            

            // Smart Wizard
            $('#smartwizard').smartWizard({
                    selected: 0,
                    theme: 'default',
                    transitionEffect:'fade',
                    showStepURLhash: false,
                    toolbarSettings: {toolbarPosition: 'top',
                                      toolbarButtonPosition: 'end'
                                    }
            });


            // External Button Events
            $("#reset-btn").on("click", function() {
                // Reset wizard
                $('#smartwizard').smartWizard("reset");
                return true;
            });

            $("#prev-btn").on("click", function() {
                // Navigate previous
                $('#smartwizard').smartWizard("prev");
                return true;
            });

            $("#next-btn").on("click", function() {
                // Navigate next
                $('#smartwizard').smartWizard("next");
                return true;
            });

            $("#theme_selector").on("change", function() {
                // Change theme
                $('#smartwizard').smartWizard("theme", $(this).val());
                return true;
            });

            // Set selected theme on page refresh
            $("#theme_selector").change();
        });
    </script>
</body>

<?php 
} else {
    header("Location: ". $_SERVER['DOCUMENT_ROOT'] ."/login.php");
}

?>