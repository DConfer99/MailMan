<?php
require "header.php";

if ($_POST['submit'] == "submit") {
    //Setting local variables
    $rootPassword = $_POST['rootPassword'];
    $new_hostname = $_POST['hostname'];
    $certbotEmail = $_POST['certbotEmail'];

    $rootExec = new rootExec;

    //Sets values for 10-ssl.conf values
    $rootExec->command("printf 'ssl = required\\n' > /etc/dovecot/conf.d/10-ssl.conf", $rootPassword);
    $rootExec->command("printf 'ssl_cert = </etc/letsencrypt/live/$hostname/fullchain.pem\\n' >> /etc/dovecot/conf.d/10-ssl.conf", $rootPassword);
    $rootExec->command("printf 'ssl_key = </etc/letsencrypt/live/$hostname/privkey.pem\\n' >> /etc/dovecot/conf.d/10-ssl.conf", $rootPassword);
    $rootExec->command("printf 'ssl_client_ca_dir = /etc/ssl/certs\\n' >> /etc/dovecot/conf.d/10-ssl.conf", $rootPassword);

    //Sets values for 10-auth.conf
    $rootExec->command("printf 'auth_mechanisms = plain login\\n' > /etc/dovecot/conf.d/10-auth.conf", $rootPassword);
    $rootExec->command("printf 'disable_plaintext_auth = yes\\n' >> /etc/dovecot/conf.d/10-auth.conf", $rootPassword);
    $rootExec->command("printf '!include auth-system.conf.ext\\n' >> /etc/dovecot/conf.d/10-auth.conf", $rootPassword);

    //Setting values for 10-master.conf
    require "scripts/10-master.conf.php";
    tenMasterConf($rootPassword);

    //Main Postfix Config File setup
    require "scripts/postfixConf.php";
    postfixConf($hostname, $rootPassword);

    //Sets up directories for users in dovecot
    require "scripts/dovecotConf.php";
    dovecotConf($rootPassword);

    //Sets up ipendkim config files
    require "scripts/opendkimConf.php";
    opendkimConf($hostname, $rootPassword);

    //Restarting all of the mail services
    $rootExec->command("systemctl restart postfix", $rootPassword);
    $rootExec->command("systemctl restart dovecot", $rootPassword);
    $rootExec->command("systemctl restart opendkim", $rootPassword);

    //Sets VirtualHost file and sets up apache
    $rootExec->command("hostnamectl set-hostname " . $new_hostname, $rootPassword);
    $rootExec->command("printf '<VirtualHost *:80>\\nServerName " . $new_hostname . "\\nDocumentRoot " . $_SERVER['DOCUMENT_ROOT'] . "\\n</VirtualHost>' > /etc/apache2/sites-available/" . $new_hostname . ".conf", $rootPassword);
    $rootExec->command("a2ensite $new_hostname", $rootPassword);
    $rootExec->command("a2dissite 000-default.conf", $rootPassword);
    $rootExec->command("systemctl reload apache2", $rootPassword);
    $rootExec->command("certbot -n --apache --agree-tos --redirect --hsts --email $certbotEmail -d $new_hostname", $rootPassword);
    $rootExec->command("systemctl reload apache2", $rootPassword);

    header("Location: http://$new_hostname");
}

//Hostname has extra cahracters. Need to trim
$hostname = shell_exec("hostname");
$hostname = trim($hostname);

//Looks to DNS server to get public IP
$pub_ip_add = shell_exec("dig +short myip.opendns.com @resolver1.opendns.com");
$pub_ip_add = trim($pub_ip_add);

if ($pub_ip_add == "") {
    $fatalErrot = new fatalError;
    $fatalErrot->displayError("This server is not connected to the public internet. Please check your network settings to ensure that they are correct and try again.");
}

?>

<body>
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="../img/logo-text.png" width="113" height="30" alt="">
        </a>
    </nav>
    <div class="container" style="padding-top: 50px;">
        <h3 class="border-bottom border-gray pb-2">Setup SSL</h3><br>
        <form method="post" action="">
        <div class="form-class">
            <label for="hostname">Host Name</label>
            <input type="text" class="form-control" id="hostname" name="hostname" value="<?php echo $hostname;?>" placeholder="Enter Hostname" aria-describedby="hostnameHelpBlock" onkeyup="myHostname()">
            <small id="hostnameHelpBlock" class="form-text text-muted">
                This should be your domain name or a subdomain of your domain name. For example, if you want your email address to be "brennan@mailman.com", your host name would be "mailman.com". Or, if you wanted you email address to be "dillon@mail.mailman.com", your host name would be "mail.mailman.com".
            </small><br>
            <div class="form-class">
                    <label for="certbotEmail">Certbot Email</label>
                    <input type="text" class="form-control" id="certbotEmail" name="certbotEmail" placeholder="you@youremail.com">
                    <small id="hostnameHelpBlock" class="form-text text-muted">
                        This is the information that will be used to create an SSL public certificate and private key for the purposes of encryption.
                    </small>
            </div>
            <br><br>
            <h4 class="border-bottom border-gray pb-2">DNS Information</h4>
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
                </tbody>
            </table>
            <small id="hostnameHelpBlock" class="form-text text-muted">
                Enter the following information in your DNS records. <b style="color: red;">Note that this process may take up to an hour to take effect and must be applied before continuing!</b> To see if DNS records are working, you can enter the following command at the terminal:<br> <i>ping</i> <i id="message_host"><?php echo $hostname; ?></i><br>
            </small><br>
        </div>
        
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rootPasswordModal">
                Apply Changes
            </button>

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
                            <button type="submit" class="btn btn-primary" name="submit" value="submit">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

<script>
function myHostname() {
  var x = document.getElementById("hostname");
  document.getElementById("dns_host").innerHTML = x.value;
  document.getElementById("message_host").innerHTML = x.value;
}
</script>

<?php 


die;

?>