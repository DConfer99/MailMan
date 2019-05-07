<?php

$db_check = shell_exec("ls ".$_SERVER["DOCUMENT_ROOT"]."/db/settings.db");

//Checks to see if settings database exists
if ($db_check == "") {
    //Sets initial values for the form
    if (!isset($_POST)) {
        echo "Hello";
    } else {
        $postfix_version = shell_exec("postconf mail_version | cut -c 16-");
        $apache_version = shell_exec("apache2 -v | grep \"Server version:\" | cut -c 17-");
        $hostname = shell_exec("hostname");
    }


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
                <li style="margin-bottom: 40px;"><a href="#step-1">Step 1<br /><small>Postfix</small></a></li>
                <li style="margin-bottom: 40px;"><a href="#step-2">Step 2<br /><small>This is step description</small></a></li>
                <li style="margin-bottom: 40px;"><a href="#step-3">Step 3<br /><small>This is step description</small></a></li>
                <li style="margin-bottom: 40px;"><a href="#step-4">Step 4<br /><small>This is step description</small></a></li>
                <li style="margin-bottom: 40px;"><a href="#step-5">Step 5<br /><small>This is step description</small></a></li>
                <li style="margin-bottom: 40px;"><a href="#step-6">Step 6<br /><small>This is step description</small></a></li>
            </ul>

            <div>
                <form method="post" action="">
                    <div id="step-0" class="">
                        <h3 class="border-bottom border-gray pb-2">Welcome to MailMan</h3>
                        Welcome to MailMan. The ultimate mail server monitoring untility! This initial setup will step you through the process of setting up your mail server. Please note that <b>you need access to your domain DNS settings and have the password for the root user on this server in order for this software to work!</b>
                    </div>
                    <div id="step-1" class="" style="display: none;">
                        <h3 class="border-bottom border-gray pb-2">Postfix Setup</h3><br>
                            <div class="form-class">
                                <label for="hostname">Host Name</label>
                                <input type="text" class="form-control" id="hostname" value="<?php echo $hostname;?>" aria-describedby="hostnameHelpBlock">
                                <small id="hostnameHelpBlock" class="form-text text-muted">
                                    This should be the same as your A record pointing to the mail server
                                </small>
                                <br>
                                <i>Installed Postfix Version: <?php echo $postfix_version;?></i>
                            </div>
                    </div>
                    <div id="step-2" class="" style="display: none;">
                        <h3 class="border-bottom border-gray pb-2">Step 2 Content</h3>
                        <div>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. </div>
                    </div>
                    <div id="step-3" class="" style="display: none;">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <div id="step-4" class="" style="display: none;">
                        <h3 class="border-bottom border-gray pb-2">Step 4 Content</h3>
                        <div class="card">
                            <div class="card-header">My Details</div>
                            <div class="card-block p-0">
                            <table class="table">
                                <tbody>
                                    <tr> <th>Name:</th> <td>Tim Smith</td> </tr>
                                    <tr> <th>Email:</th> <td>example@example.com</td> </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div id="step-6" class="" style="display: none;">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rootPasswordModal">
                            Apply Changes
                        </button>
                    </div>
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
                                    This action requires a root priviledges on this server in order to execute. Please enter the password for the root user.<br><br>
                                    <input type="password" class="form-control" id="rootPassword" placeholder="Root Password">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
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

<?php } ?>