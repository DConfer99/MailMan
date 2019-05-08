<?php
class fatalError {

    private function getImgURL() {
        $imgURL = "img/logo-text.png";
        return $imgURL;
    }

    public function displayError($error) {
        echo "<img src='" . $this->getImgURL() . "' width=375px height=100px><br><h1>Operation Has Stopped</h1><p>" . $error . "</p>";
        die;
    }
}


class rootExec {
    public function command($command, $password) {
        shell_exec("./scripts/runas.sh \"$command\" root $password");
    }
}


?>
