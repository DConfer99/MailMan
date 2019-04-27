<?php
class fatalError {

    public function getImgURL() {
        $imgURL = "img/logo-text.png";
        return $imgURL;
    }

    public function displayError($error) {
        echo "<img src='" . $this->getImgURL() . "' width=375px height=100px><br><h1>Operation Has Stopped</h1><p>" . $error . "</p>";
        die;
    }
}


?>
