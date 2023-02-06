<?php

/* validate */
if(!$_SESSION['id'] && $_GET['job'] != 'login') $_GET['job'] = 'login';
else if($_SESSION['id'] && $_GET['job'] == 'login') $_GET['job'] = 'home';

$pageTitle = strtoupper($_GET['job']) ;

if (file_exists('pages/'.$_GET['job'].'.php')) {
    if(!empty($_SESSION['id'])){
        checkAccount();
        checkSession();
        updateLastSeen();
    }
    print str_replace(['{{DIR_FROM_ROOT}}','{{PAGE_TITLE}}'],[_HOME_._DIR_FROM_ROOT_,$pageTitle],file_get_contents(_STYLE_.'head.rings'));
    require_once 'include/interface.php';
    require_once 'pages/'.$_GET['job'].'.php';
}
else { $error['code'] = '404';$error['files'] = 'Main'; $error['details'] = 'Not Found 👀'; require_once 'pages/error.php'; }