<?php

function dovecotConf($rootPassword) {
    //Makes sure the script is empty for 
    shell_exec("echo \"\" > scripts/dovecotInit");

    $file = fopen("scripts/dovecotInit", "w") or die;

    $config = "##
## Mailbox definitions
##

# Each mailbox is specified in a separate mailbox section. The section name
# specifies the mailbox name. If it has spaces, you can put the name
# \"in quotes\". These sections can contain the following mailbox settings:
#
# auto:
#   Indicates whether the mailbox with this name is automatically created
#   implicitly when it is first accessed. The user can also be automatically
#   subscribed to the mailbox after creation. The following values are
#   defined for this setting:
# 
#     no        - Never created automatically.
#     create    - Automatically created, but no automatic subscription.
#     subscribe - Automatically created and subscribed.
#  
# special_use:
#   A space-separated list of SPECIAL-USE flags (RFC 6154) to use for the
#   mailbox. There are no validity checks, so you could specify anything
#   you want in here, but it's not a good idea to use flags other than the
#   standard ones specified in the RFC:
#
#     \All      - This (virtual) mailbox presents all messages in the
#                 user's message store. 
#     \Archive  - This mailbox is used to archive messages.
#     \Drafts   - This mailbox is used to hold draft messages.
#     \Flagged  - This (virtual) mailbox presents all messages in the
#                 user's message store marked with the IMAP \Flagged flag.
#     \Junk     - This mailbox is where messages deemed to be junk mail
#                 are held.
#     \Sent     - This mailbox is used to hold copies of messages that
#                 have been sent.
#     \Trash    - This mailbox is used to hold messages that have been
#                 deleted.
#
# comment:
#   Defines a default comment or note associated with the mailbox. This
#   value is accessible through the IMAP METADATA mailbox entries
#   \"/shared/comment\" and \"/private/comment\". Users with sufficient
#   privileges can override the default value for entries with a custom
#   value.

# NOTE: Assumes \"namespace inbox\" has been defined in 10-mail.conf.
namespace inbox {
    # These mailboxes are widely used and could perhaps be created automatically:postfixInit
    mailbox Drafts {
    auto = create
    special_use = \Drafts
    }
    mailbox Junk {
    auto = create
    special_use = \Junk
    }
    mailbox Trash {
    auto = create
    special_use = \Trash
    }

    # For \Sent mailboxes there are two widely used names. We'll mark both of
    # them as \Sent. User typically deletes one of them if duplicates are created.
    mailbox Sent {
    auto = create
    special_use = \Sent
    }
    #mailbox \"Sent Messages\" {
    #special_use = \Sent
    #}

    # If you have a virtual \"All messages\" mailbox:
    #mailbox virtual/All {
    #  special_use = \All
    #  comment = All my messages
    #}

    # If you have a virtual \"Flagged\" mailbox:
    #mailbox virtual/Flagged {
    #  special_use = \Flagged
    #  comment = All my flagged messages
    #}
}
";

    fwrite($file, $config);
    fclose($file);

    
    $tempRoot = new rootExec;
    $tempRoot->command("mv /var/www/html/scripts/dovecotInit /etc/dovecot/conf.d/15-mailboxes.conf", $rootPassword);
    $tempRoot->command("chown root:root /etc/dovecot/conf.d/15-mailboxes.conf", $rootPassword);
    $tempRoot->command("chmod 644 /etc/dovecot/conf.d/15-mailboxes.conf", $rootPassword);
    $tempRoot->command("adduser dovecot mail", $rootPassword);
    $tempRoot->command("systemctl restart dovecot", $rootPassword);
}



?>