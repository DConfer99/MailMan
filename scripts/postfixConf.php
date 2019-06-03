<?php

function postfixConf($hostname, $rootPassword) {
    //Makes sure the script is empty for 
    shell_exec("echo \"\" > scripts/postfixInit");

    $file = fopen("scripts/postfixInit", "w") or die;

    $config = "# See /usr/share/postfix/main.cf.dist for a commented, more complete version

# Debian specific:  Specifying a file name will cause the first
# line of that file to be used as the name.  The Debian default
# is /etc/mailname.
#myorigin = /etc/mailname

smtpd_banner = \$myhostname ESMTP \$mail_name (Ubuntu)
biff = no

# appending .domain is the MUA's job.
append_dot_mydomain = no

# Uncomment the next line to generate \"delayed mail\" warnings
#delay_warning_time = 4h

readme_directory = no

# See http://www.postfix.org/COMPATIBILITY_README.html -- default to 2 on
# fresh installs.
compatibility_level = 2

# TLS parameters
smtpd_tls_cert_file=/etc/letsencrypt/live/mail.dillonconfer.com/fullchain.pem
smtpd_tls_key_file=/etc/letsencrypt/live/mail.dillonconfer.com/privkey.pem
smtpd_tls_security_level=may
smtpd_tls_protocols = !SSLv2, !SSLv3, !TLSv1
smtpd_tls_loglevel= 1
smtpd_use_tls=yes
smtpd_tls_session_cache_database = btree:\${data_directory}/smtpd_scache

smtp_tls_security_level = may
smtp_tls_loglevel = 1
smtp_tls_session_cache_database = btree:\${data_directory}/smtp_scache

# See /usr/share/doc/postfix/TLS_README.gz in the postfix-doc package for
# information on enabling SSL in the smtp client.

smtpd_relay_restrictions = permit_mynetworks permit_sasl_authenticated defer_unauth_destination
myhostname = mail.dillonconfer.com
alias_maps = hash:/etc/aliases
alias_database = hash:/etc/aliases
myorigin = /etc/mailname
mydestination = \$myhostname, mail.dillonconfer.com, localhost.dillonconfer.com, , localhost
relayhost = 
mynetworks = 127.0.0.0/8 [::ffff:127.0.0.0]/104 [::1]/128
mailbox_size_limit = 0
recipient_delimiter = +
inet_interfaces = all
inet_protocols = all

policyd-spf_time_limit = 3600
smtpd_recipient_restrictions =
    permit_mynetworks,
    permit_sasl_authenticated,
    reject_unauth_destination,
    check_policy_service unix:private/policyd-spf
milter_default_action = accept
milter_protocol = 6
smtpd_milters = local:opendkim/opendkim.sock
non_smtpd_milters = \$smtpd_milters";

    fwrite($file, $config);
    fclose($file);

    
    $tempRoot = new rootExec;
    $tempRoot->command("mv /var/www/html/scripts/postfixInit /etc/postfix/main.cf", $rootPassword);
    $tempRoot->command("chown root:root /etc/postfix/main.cf", $rootPassword);
    $tempRoot->command("chmod 644 /etc/postfix/main.cf", $rootPassword);

    //Appends info into /etc/postfix/master.cf
    $tempRoot->command("printf 'submission     inet     n    -    y    -    -    smtpd
 -o syslog_name=postfix/submission
 -o smtpd_tls_security_level=encrypt
 -o smtpd_tls_wrappermode=no
 -o smtpd_sasl_auth_enable=yes
 -o smtpd_relay_restrictions=permit_sasl_authenticated,reject
 -o smtpd_recipient_restrictions=permit_mynetworks,permit_sasl_authenticated,reject
 -o smtpd_sasl_type=dovecot
 -o smtpd_sasl_path=private/auth
 
 policyd-spf  unix  -       n       n       -       0       spawn
    user=policyd-spf argv=/usr/bin/policyd-spf' >> /etc/postfix/master.cf", $rootPassword);
    $tempRoot->command("systemctl restart postfix", $rootPassword);
}



?>