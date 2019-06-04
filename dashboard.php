<?php require "session.php"; ?>

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
    <script type="text/javascript" src="//cdn.jsdelivr.net/snap.svg/0.1.0/snap.svg-min.js"></script>

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

</head>

<?php
///////////////////
//Hard Drive Info//
///////////////////

$free_hard_drive = shell_exec("df -h --output=source,avail / | grep / | cut -c 12-");
trim($free_hard_drive);

$used_hard_drive = shell_exec("df -h --output=source,used / | grep / | cut -c 12-");
trim($used_hard_drive);

$total_hard_drive = shell_exec("df -h --output=source,size / | grep / | cut -c 12-");
trim($total_hard_drive);

$percent_hard_drive = shell_exec("df -h --output=source,pcent / | grep / | cut -c 12-");
trim($percent_hard_drive);
$percent_hard_drive = substr($percent_hard_drive, 0, -2);


///////////////
//Memory Info//
///////////////

$free_memory = shell_exec("free -h --si | grep Mem | tr -s ' ' | cut -d ' ' -f 4");
$used_memory = shell_exec("free -h --si | grep Mem | tr -s ' ' | cut -d ' ' -f 3");
$total_memory = shell_exec("free -h --si | grep Mem | tr -s ' ' | cut -d ' ' -f 2");
$percent_memory = shell_exec("free | grep Mem | awk '{print $3/$2 * 100.0}'");
$percent_memory = explode('.', $percent_memory);


////////////
//CPU Info//
////////////

$name_cpu = shell_exec("cat /proc/cpuinfo | grep -m1 \"model name\" | tr -s ' ' | cut -d ' ' -f 3-");
$num_cpu = shell_exec("cat /proc/cpuinfo | grep processor | wc -l");
$percent_cpu = shell_exec("top -b -d 1 | grep -m1 Cpu | tr -s ' ' | cut -d ' ' -f 2");
$percent_cpu_whole = explode('.', $percent_cpu);


?>

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
                <div class="card-deck" style="padding-bottom: 20px; padding-left: 10px; padding-right: 10px;">
                    <div class="card w-25 text-center">
                        <div class="card-body">
                            <h5 class="card-title">Hard Disk Usage</h5>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: <?php echo $percent_hard_drive; ?>%;" aria-valuenow="<?php echo $percent_hard_drive; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percent_hard_drive; ?>%</div>
                            </div>
                            <p class="card-text">Total Space: <?php echo $total_hard_drive; ?></p>
                            <p class="card-text">Free Space: <?php echo $free_hard_drive; ?></p>
                            <p class="card-text">Used Space: <?php echo $used_hard_drive; ?></p>
                        </div>
                    </div>
                    <div class="card w-25 text-center">
                        <div class="card-body">
                            <h5 class="card-title">Memory Usage</h5>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: <?php echo $percent_memory[0]; ?>%;" aria-valuenow="<?php echo $percent_memory[0]; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percent_memory[0]; ?>%</div>
                            </div>
                            <p class="card-text">Total Memory: <?php echo $total_memory; ?></p>
                            <p class="card-text">Free Memory: <?php echo $free_memory; ?></p>
                            <p class="card-text">Used Memory: <?php echo $used_memory; ?></p>
                        </div>
                    </div>
                    <div class="card w-25 text-center">
                        <div class="card-body">
                            <h5 class="card-title">Processor Information</h5>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: <?php echo $percent_cpu_whole[0]; ?>%;" aria-valuenow="<?php echo $percent_cpu_whole[0]; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percent_cpu; ?>%</div>
                            </div>
                            <p class="card-text">Model: <?php echo $name_cpu; ?></p>
                            <p class="card-text">Processor Count: <?php echo $num_cpu; ?></p>
                        </div>
                    </div>
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
