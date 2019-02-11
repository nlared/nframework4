<?php
require_once 'include.php';
if (isset($_SESSION['webcam'])) {
    if ($_POST['type'] == "pixel") {
        // input is in format 1,2,3...|1,2,3...|...
        $im = imagecreatetruecolor(320, 240);
        foreach (explode("|", $_POST['image']) as $y => $csv) {
            foreach (explode(";", $csv) as $x => $color) {
                imagesetpixel($im, $x, $y, $color);
            }
        }
    } else {
        // input is in format: data:image/png;base64,...
        $im = imagecreatefrompng($_POST['image']);
    }
    eval($_SESSION['webcam']);
    imagedestroy($im);
}