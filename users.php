<?php
require "classes.php";

//Looks to see if a form was submitted
if ($_POST['submit'] != "") {

    if ($_POST['username'] != "" && $_POST['submit'] == "newUser") {
        //Setting local variables for script
        $username = $_POST['username'];
        $password = $_POST['password'];
        $rootPassword = $_POST['rootPassword'];

        //Command to execute new user command
        $rootExec = new rootExec;
        $rootExec->command("useradd -m -p '$(mkpasswd -m sha-512 $password)' -s /bin/bash " . $username, $rootPassword);
        
    } else {
        //This is triggered when a deleted user is selected
        $username = $_POST['submit'];
        $rootPassword = $_POST[$username];

        //Removes user from system as well as their home directory
        $rootExec = new rootExec;
        $rootExec->command("userdel " . $username, $rootPassword);
        $rootExec->command("rm -rf /home/" . $username, $rootPassword);
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Dashboard</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

</head>

<body class="animsition">
    <div class="page-wrapper">

        <?php
        require "navMobile.php";
        require "sidebar.php";
        ?> 

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            
            <?php require "navDesktop.php"; ?>

            <!-- MAIN CONTENT-->
            <div class="main-content">

            <?php
            $rootExec = new rootExec;
            $hostname = shell_exec("hostname");
            $users = shell_exec("ls /home");
            $users = explode("\n",$users);
            array_pop($users);
            ?>
        <form action="" method="post">
            <div class="row">
                            <div class="col-lg-9">
                                <div class="table-responsive table--no-card m-b-30">
                                    <table class="table table-borderless table-striped table-earning">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Email Address</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                                foreach ($users as $user) {
                                                    echo "<tr>";
                                                    echo "<td>". $user ."</td>";
                                                    echo "<td>" . $user . "@" . $hostname . "</td>";
                                                    echo "<td class=\"text-right\"><button type=\"button\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#$user\" data-value=\"$user\"><i class=\"fas fa-user-times\"></i> Delete</button></td>";
                                                    echo "</tr>";
                                                    $userLabel = $user . "Label";
                                                    echo "
                                            
                                                        <div class=\"modal fade\" id=\"$user\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"$userLabel\" aria-hidden=\"true\">
                                                                    <div class=\"modal-dialog\" role=\"document\">
                                                                        <div class=\"modal-content\">
                                                                            <div class=\"modal-header\">
                                                                                <h5 class=\"modal-title\" id=\"$userLabel\">Delete User</h5>
                                                                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                                                                                <span aria-hidden=\"true\">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class=\"modal-body\">
                                                                                WARNING! This action will delete the user $user along with their files. This is irreversable. This action requires root privileges on this server in order to execute. Please enter the password for the root user.<br><br>
                                                                                <input type=\"password\" class=\"form-control\" id=\"rootPassword\" name=\"$user\" placeholder=\"Root Password\">
                                                                            </div>
                                                                            <div class=\"modal-footer\">
                                                                                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Close</button>
                                                                                <button type=\"submit\" class=\"btn btn-primary\" name=\"submit\" value=\"$user\">Save changes</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                
                                                        </div>";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                
                                <h3>Add A User</h3><br>
                                <input class="au-input au-input--md" type="text" name="username" placeholder="Username"><br>
                                <input class="au-input au-input--md" type="password" name="password" placeholder="Password"><br>
                                <input class="au-input au-input--md" type="password" name="passwordVerify" placeholder="Verify Password">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#rootPasswordModal"><i class="fas fa-user-plus"></i> Add User</button>

                            </div>

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
                                    This action requires root privileges on this server in order to execute. Please enter the password for the root user.<br><br>
                                    <input type="password" class="form-control" id="rootPassword" name="rootPassword" placeholder="Root Password">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="submit" value="newUser">Save changes</button>
                                </div>
                            </div>
                        </div>
                    
            </div>


        </form>
        
            <!-- END MAIN CONTENT-->


            <!-- END PAGE CONTAINER-->
            
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">class="au-input au-input--md"
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js">
    </script>

    <!-- Main JS-->
    <script src="js/main.js"></script>

</body>

</html>
<!-- end document-->
