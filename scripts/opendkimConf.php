<?php

function opendkimConf($hostname, $rootPassword) {
    $confFile = "# This is a basic configuration that can easily be adapted to suit a standard
# installation. For more advanced options, see opendkim.conf(5) and/or
# /usr/share/doc/opendkim/examples/opendkim.conf.sample.

# Log to syslog
Syslog
yes

# Required to use local socket with MTAs that access the socket as a non-
# privileged user (e.g. Postfix)
UMask
007

# Sign for example.com with key in /etc/dkimkeys/dkim.key using
# selector '2007' (e.g. 2007._domainkey.example.com)

#Domain
example.com

#KeyFile
/etc/dkimkeys/dkim.key

#Selector
2007

# Commonly-used options; the commented-out versions show the defaults.

Canonicalization
relaxed/simple

Mode
sv

SubDomains
no

AutoRestart             yes
AutoRestartRate         10/1M
Background              yes
DNSTimeout              5

SignatureAlgorithm      rsa-sha256
# Socket smtp://localhost
#
# ##  Socket socketspec
# ##
# ##  Names the socket where this filter should listen for milter connections
# ##  from the MTA.  Required.  Should be in one of these forms:
# ##
# ##  inet:port@address           to listen on a specific interface
# ##  inet:port                   to listen on all interfaces
# ##  local:/path/to/socket       to listen on a UNIX domain socket
#
#Socket                  inet:8892@localhost
Socket
local:/var/spool/postfix/opendkim/opendkim.sock

##  PidFile filename
###      default (none)
###
###  Name of the file where the filter should write its pid before beginning
###  normal operations.
#
PidFile               /var/run/opendkim/opendkim.pid

# Always oversign From (sign using actual From and a null From to prevent
# malicious signatures header fields (From and/or others) between the signer
# and the verifier.  From is oversigned by default in the Debian pacakge
# because it is often the identity key used by reputation systems and thus
# somewhat security sensitive.
OversignHeaders
From
##  ResolverConfiguration filename
##      default (none)
##
##  Specifies a configuration file to be passed to the Unbound library that
##  performs DNS queries applying the DNSSEC protocol.  See the Unbound
##  documentation at http://unbound.net for the expected content of this file.
##  The results of using this and the TrustAnchorFile setting at the same
##  time are undefined.
##  In Debian, /etc/unbound/unbound.conf is shipped as part of the Suggested
##  unbound package

# ResolverConfiguration     /etc/unbound/unbound.conf
##  TrustAnchorFile filename
##      default (none)
##
## Specifies a file from which trust anchor data should be read when doing
## DNS queries and applying the DNSSEC protocol.  See the Unbound documentation
## at http://unbound.net for the expected format of this file.

TrustAnchorFile       /usr/share/dns/root.key

##  Userid userid
###      default (none)
###
###  Change to user \"userid\" before starting normal operation?  May include
###  a group ID as well, separated from the userid by a colon.
#

UserID                opendkim
KeyTable            refile:/etc/opendkim/key.table
SigningTable        refile:/etc/opendkim/signing.table
ExternalIgnoreList  /etc/opendkim/trusted.hosts
InternalHosts /etc/opendkim/trusted.hosts";

    $tempRoot = new rootExec;
    $tempRoot->command("printf '$confFile' > /etc/opendkim.conf", $rootPassword);
    $tempRoot->command("mkdir /etc/opendkim", $rootPassword);
    $tempRoot->command("mkdir /etc/opendkim/keys", $rootPassword);
    $tempRoot->command("chown -R opendkim:opendkim /etc/opendkim", $rootPassword);
    $tempRoot->command("chmod go-rw /etc/opendkim/keys", $rootPassword);
    $tempRoot->command("chmod 644 /etx/opendkim/keys/$hostname/default.txt", $rootPassword);
    $tempRoot->command("printf '*@$hostname    default._domainkey.$hostname' > /etc/opendkim/signing.table", $rootPassword);
    $tempRoot->command("printf 'default._domainkey.$hostname     $hostname:default:/etc/opendkim/keys/$hostname/default.private' > /etc/opendkim/key.table", $rootPassword);
    $tempRoot->command("printf '127.0.0.1 \\nlocalhost \\n*.$hostname' > /etc/opendkim/trusted.hosts", $rootPassword);
    $tempRoot->command("mkdir /etc/opendkim/keys/$hostname", $rootPassword);
    $tempRoot->command("opendkim-genkey -b 2048 -d $hostname -D /etc/opendkim/keys/$hostname -s default -v", $rootPassword);
    $tempRoot->command("chown opendkim:opendkim /etc/opendkim/keys/$hostname/default.private", $rootPassword);
    $tempRoot->command("mkdir -p /var/spool/postfix/opendkim", $rootPassword);
    $tempRoot->command("chown opendkim:postfix /var/spool/postfix/opendkim", $rootPassword);
    $tempRoot->command("sed -i '/SOCKET=local/c\SOCKET=local:/var/spool/postfix/opendkim/opendkim.sock' /etc/default/opendkim", $rootPassword);
    $tempRoot->command("sed -i '/local:/var/run/opendkim/opendkim.sock/c\Socket                  local:/var/spool/postfix/opendkim/opendkim.sock' /etc/opendkim.conf", $rootPassword);
    
}

?>