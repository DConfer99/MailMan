<!DOCTYPE html>
<html lang="en">
<?php require "session.php"; ?>
    <?php 
        require "classes.php";

        if ($_POST['submit'] != "") {

            $mailbox_size_limit = $_POST['mailbox_size_limit'];
            $message_size_limit = $_POST['message_size_limit'];
            $rootPassword = $_POST['rootPassword'];

            $rootExec = new rootExec;
            $rootExec->command("sudo postconf -e mailbox_size_limit=" . $mailbox_size_limit, $rootPassword);
            $rootExec->command("sudo postconf -e message_size_limit=" . $message_size_limit, $rootPassword);

            
        }

        $mailbox_size_limit = shell_exec("postconf mailbox_size_limit | cut -c 22-");
        $message_size_limit = shell_exec("postconf message_size_limit | cut -c 22-");
    ?>

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Options</title>

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
                <form action="" method="post">

                <div class="col-lg-12">
                <div class="user-data m-b-30">
                                    <h3 class="title-3 m-b-30">
                                        <i class="fas fa-table"></i>options</h3>
                                    <div class="table-responsive table-data">

                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="table-data__info">
                                                        Mailbox size
                                                        <input class="au-input au-input--xl" type="text" name="mailbox_size_limit" value="<?php echo $mailbox_size_limit; ?>"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Message Size
                                                        <input class="au-input au-input--xl" type="text" name="message_size_limit" value="<?php echo $message_size_limit; ?>"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#rootPasswordModal">Save Changes</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
                                    <button type="submit" class="btn btn-primary" name="submit" value="saveChanges">Save changes</button>
                                </div>
                            </div>
                        </div>
                </form>
                    
            </div>





            </div>

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
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
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
