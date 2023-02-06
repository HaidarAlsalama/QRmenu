<?php
$pageTitle = strtoupper(str_replace('_',' ',$_GET['all'][2 - _HOST_])) ;
if (file_exists('pages/public/'.$_GET['all'][2 - _HOST_].'.php')) {
    print str_replace(['{{DIR_FROM_ROOT}}','{{PAGE_TITLE}}'],[_HOME_._DIR_FROM_ROOT_,$pageTitle],file_get_contents(_STYLE_.'head.rings'));
    require_once 'include/interface.php';
    require_once 'pages/public/'.$_GET['all'][2 - _HOST_].'.php';
}
else { $error['code'] = '404'; $error['files'] = 'Main'; $error['details'] = 'Not Found 👀'; require_once 'pages/error.php'; }
