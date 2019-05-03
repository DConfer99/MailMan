<?php

if (shell_exec("ls scripts/runas.sh") == "") {
    shell_exec("touch scripts/runas.sh");
    shell_exec("chmod 755 scripts/runas.sh");
}

$runas_hash = "a0c6a8a9081e587abee60a205a453526";
if (md5_file("scripts/runas.sh") != $runas_hash) {
    shell_exec("echo \"\" > scripts/runas.sh");
    $file = fopen("scripts/runas.sh", "w") or die;
    
    $script = "#!/usr/bin/expect -f
#Usage: script.sh cmd user pass

set cmd [lindex \$argv 0];
set user [lindex \$argv 1];
set pass [lindex \$argv 2];

log_user 0
spawn su -c \$cmd - \$user
expect \"Password: \"
log_user 1
send \"\$pass\\r\"
expect \"\$ \"";

    fwrite($file, $script);
    fclose($file);

}

?>