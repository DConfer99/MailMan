<?php
//Command to look for expect
$expect_check = "which expect";

//Checks to see if expect is installed on the server
if (shell_exec($expect_check) == "") {
    $fatalError = new fatalError;
    $fatalError->displayError("Expect is not installed on your system. Please install it by using the command <i>sudo apt install -y expect</i>");
}

//command to install missing packages
$install_command = "DEBIAN_FRONTEND=noninteractive apt install -y postfix certbot python3-certbot-apache dovecot-core postfix-policyd-spf-python opendkim opendkim-tools whois";

//Runs when modal is activated
if($_POST['submit'] == "submit"){
    $root_password = $_POST['rootPassword'];
    $rootExec = new rootExec;
    $rootExec->command("apt-add-repository ppa:cerbot/cerbot", $root_password);
    $rootExec->command("apt update", $root_password);
    $rootExec->command($install_command, $root_password);
}

//TODO
//1: Add error for wrong password
//2: Add support for sudoers

//Command to check and see what packages are installed
$package_status = shell_exec("apt list postfix certbot python3-certbot-apache dovecot-core postfix-policyd-spf-python opendkim opendkim-tools whois");

//Puts list of packages into an array
$package_array = preg_split('/\n+/', trim($package_status));

//First element of the array is garbage and does not contain package info
unset($package_array[0]);

//Counter for the missing packages on the system
$missing_packages = 0;

//Initialize an empty array for the installed bools
$package_installed = array();

//Goes through the package_array and determines if the packages are installed
foreach ($package_array as $package) {
    if (fnmatch("*installed*", $package)) {
        array_push($package_installed, 1);
    } else {
        array_push($package_installed, 0);
        $missing_packages++;
    }
}

//This code trips and runs if a package is missing
if( $missing_packages >= 1 ){

    require "header.php";
    ?>

    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="../img/logo-text.png" width="113" height="30" alt="">
        </a>
    </nav>
<div class="container">
    <br />
    <form action="" method="POST">
                    <div id="step-2" class="" style="disp">
                        <h3 class="border-bottom border-gray pb-2">Package Check</h3>
                        <div>
                        
                            <?php 
                            #use foreach here
                                $packages = array("certbot", "dovecot-core", "opendkim", "opendkim-tools", "postfix", "postfix-policyd-spf-python", "python3-certbot-apache", "whois");
                                $array_count = 0;
                                foreach ($package_installed as $status) {

                                    if($status == 1){
                                        ?> <i class="fas fa-check-circle" style="color: green;"></i> <i><?php echo $packages[$array_count]; ?></i> is installed! <?php
                                    } else {
                                        ?> <i class="fas fa-times-circle" style="color: red;"></i> <i><?php echo $packages[$array_count]; ?></i> is not installed. <?php
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
                                    <button type="submit" class="btn btn-primary" name="submit" value="submit">Save changes</button>
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