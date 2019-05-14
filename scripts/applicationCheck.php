<?php
/*
//Runs command to see if postfix is installed
$postfix_check = shell_exec("which postfix");

//Stops operation if postfix is not installed
if ($postfix_check == "") {
    $err = new fatalError;
    $err->displayError("Postfix is not installed on this server. Please install using the command: \n <i>DEBIAN_FRONTEND=noninteractive apt-get install postfix</i> ");
}

//Looks to see the exsistance and permissions of /etc/postfix/main.cf
$postfix_config_check = shell_exec("ls -l /etc/postfix/main.cf");

//Halts operationif /etc/postfix/main.cf does not exist
if ($postfix_config_check == "") {
    $err = new fatalError;
    $err->displayError("Postfix config file does not exist. Please execute the command: <i>sudo touch /etc/postfix/main.cf</i>");
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
*/
?>
<?php 
$install_command= "apt install -y";

$packages = array("postfix", "certbot", "python3-certbot-apache", "dovecot-core", "dovecot-imapd", "postfix-policyd-spf-python", "opendkim");
$array_count = 0;
foreach ($packages as $package) {
    if(shell_exec("which " . $package) != ""){
        unset($packages[$array_count]);
    } else {
        $install_command=$install_command . " " . $package;
    }
$array_count++;
}
$packages = array_values($packages);

if( count($packages) != 0 ){

    if(isset($_GET['submit'])){
        $rootExec = new rootExec;
        $rootExec->command($install_command, $_GET['rootPassword']);
    }

    require "header.php";
    ?>

    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="../img/logo-text.png" width="113" height="30" alt="">
        </a>
    </nav>
<div class="container">
    <br />
    <form>
                    <div id="step-2" class="" style="disp">
                        <h3 class="border-bottom border-gray pb-2">Package Check</h3>
                        <div>
                        
                            <?php 
                            #use foreach here
                                $packages = array("postfix", "certbot", "python3-certbot-apache", "dovecot-core", "dovecot-imapd", "postfix-policyd-spf-python", "opendkim");
                                $array_count = 0;
                                foreach ($packages as $package) {

                                    if(shell_exec("which " . $package) != ""){
                                        ?> <i class="fas fa-check-circle" style="color: green;"></i> <i><?php echo $package; ?></i> is installed! <?php
                                        unset($packages[$array_count]);
                                    } else {
                                        ?> <i class="fas fa-times-circle" style="color: red;"></i> <i><?php echo $package; ?></i> is not installed. <?php
                                    }
                                    ?><br /><?php
                                    $array_count++;
                                }
                            $packages = array_values($packages);
                            ?>
                        </div>
                    </div>
                    <br />
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rootPasswordModal">
                            Install Now
                    </button>

                    <div class="modal fade" id="rootPasswordModal" tabindex="-1" role="dialog" aria-labelledby="rootPasswordModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rootPasswordModalLabel">Root Priviledges Required</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    This action requires a root privileges on this server in order to execute. Please enter the password for the root user.<br><br>
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
    
    <?php
    die;
}
?>
</div>