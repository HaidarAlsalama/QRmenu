<?php

$GLOBALS['connectionSQL'] = mysqli_connect('localhost', 'root', '', 'hdr');
//$GLOBALS['connectionSQL'] = mysqli_connect('byrings.sy:3306', 'byrings', 'xvBlv_^|BMCji10', 'byrings_hdr');
//$GLOBALS['connectionSQL'] = mysqli_connect('sql113.epizy.com', 'epiz_32621437', 'gSgmb063UyPM3Bt', 'epiz_32621437_hdr');

if (!$GLOBALS['connectionSQL']) {
    $error['code'] = 'SQL1040';
    $error['files'] = 'dataBase';
    $error['details'] = 'Error connection';

    require 'pages/error.php';
}
?>

