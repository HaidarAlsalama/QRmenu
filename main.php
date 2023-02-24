<?php

error_reporting(E_ALL^E_WARNING^E_NOTICE);

if(PHP_VERSION_ID < 70300)
{
    die("I'm sorry your php version is old");
}

//$home = $_SERVER['PHP_SELF'];
$home = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$home;
//$home = 'http://'.$_SERVER['SERVER_NAME'].$home;

define('_HOME_',$home);
define('_APPLICATION_NAME_','QR menu');
define('_VERSION_','V1.2');
define('_DIR_FROM_ROOT_','/nemo/'); /** **/
define('_COOKIE_NAME_','nemo');
define('_STYLE_','style/');
define('_FORCE_SSL_',true);
define('_HOST_',0); /** **/
date_default_timezone_set('Asia/Damascus');

require_once 'include/clean.php';
require_once 'include/session.php';
require_once 'include/database.php';
require_once 'include/function.php';
require_once 'include/upload_image.php';
require_once 'classes/style.php';
require_once 'classes/job.php';
require_once 'phpqrcode/qrlib.php';
//dump(json_encode(getUserInfo('all')));

session_start($options);
//dump($_GET);
$_SESSION['language'] ? define('_LANG_LIST_',$_SESSION['language']) : define('_LANG_LIST_','en'); // ['ar', 'en']
require_once 'languages/language_'._LANG_LIST_.'.php';
if($_GET['job'] == 'test')
{
    require_once 'forMain/test.php';
}
else if($_GET['job'] == 'process')
{
    require_once 'forMain/ajax.php';
}
else if($_GET['job'] == 'api'){
    require_once 'forMain/api.php';
}
else
{
    new style();
    new job();
    job::out();
}


//Array ( [pages] => process [all] => Array ( [0] => nemo [1] => process [2] => process.php ) )


