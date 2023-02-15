<?php
function dump($h=null)
{
    print '<pre dir="ltr">';
//    var_dump($h);
    print_r($h);
    print '</pre>';
}

function scriptActive($string=null,$dad=null){
    if($_GET['job'] != 'public'){
        if($dad == null)
            return "<script>$(\"a[href='"._HOME_._DIR_FROM_ROOT_.$string."']\").addClass(\"active\");</script>";
        else{
            return "<script>$(\"a[href='"._HOME_._DIR_FROM_ROOT_.$string."']\").addClass(\"active\");</script>".
                "<script>$(\"a[href='"._HOME_._DIR_FROM_ROOT_.$string."']\").parent().parent().parent().addClass(\"menu-open\");</script>";
        }
    }else{
        return "<script>$(\"a[href='"._HOME_._DIR_FROM_ROOT_.$_GET['job'].'/'.$_GET['all'][2 - _HOST_]."']\").addClass(\"active\");</script>";
    }
}

/***************************************************************************************/
//// =====>  Start Function For User ////
function getUserInfo($userId){

    if ($userId == 'all')
    {
        $query = "SELECT `id`, `login`, `name`, `username`, `e-mail`, `mobile`, `language`, `parent_id`,
                    `who_added`, `address`, `register_date`, `status`, `ncc` FROM `users`";
        $result = mysqli_query($GLOBALS['connectionSQL'], $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);

    }
    elseif (is_numeric($userId))
    {
        $query = "SELECT `id`, `login`, `name`, `username`, `password`, `e-mail`, `mobile`, `language`, `parent_id`,
                    `who_added`, `address`, `register_date`, `status`, `ncc` FROM `users` WHERE `id` = '$userId' LIMIT 1";
        $result = mysqli_query($GLOBALS['connectionSQL'], $query);
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else
    {
        $query = "SELECT `id`, `login`, `name`, `username`, `password`, `e-mail`, `mobile`, `language`, `parent_id`,
                    `who_added`, `address`, `register_date`, `status`, `ncc` FROM `users` WHERE `username` = '$userId' LIMIT 1";
        $result = mysqli_query($GLOBALS['connectionSQL'], $query);
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }


    $userArrayInfo = array();

    if($users)
    {
        foreach ($users as $user)
        {
            $userArrayInfo['id']            = $user['id'];
            $userArrayInfo['login']         = $user['login'];
            $userArrayInfo['name']          = $user['name'];
            $userArrayInfo['username']      = $user['username'];
            $userArrayInfo['password']        = $user['password'];
            $userArrayInfo['e-mail']        = $user['e-mail'];
            $userArrayInfo['mobile']        = $user['mobile'];
            $userArrayInfo['parent_id']     = $user['parent_id'];
            $userArrayInfo['parent_name']   = getParentName($user['parent_id']);
            $userArrayInfo['who_added']     = getParentName($user['who_added']);
            $userArrayInfo['address']       = $user['address'];
            $userArrayInfo['register_date'] = $user['register_date'];
            $userArrayInfo['status']        = $user['status'];
            $userArrayInfo['language']  = $user['language'];
            $userArrayInfo['ncc']  = $user['ncc'];

            if($user['language'] == 'en') $userArrayInfo['language_name'] = 'English';
            else $userArrayInfo['language_name'] = 'العربية';
        }
        return $userArrayInfo;
    }
}

function getUserMoreInfo($userId){

    $query = "SELECT * FROM `sessions` WHERE `user_id` = '$userId' LIMIT 1";
    $result = mysqli_query($GLOBALS['connectionSQL'], $query);
    $userSession = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $userArrayMoreInfo = array();

    if($userSession)
    {
        foreach ($userSession as $user)
        {
            $userArrayMoreInfo['id']            = $user['user_id'];
            $userArrayMoreInfo['name']   = getUserInfo($user['user_id'])['name'];
            $userArrayMoreInfo['device']            = $user['device'];
            $userArrayMoreInfo['os']            = $user['os'];
            $userArrayMoreInfo['browser']            = $user['browser'];
            $userArrayMoreInfo['ip']            = $user['ip'];
            $userArrayMoreInfo['last_seen']            = $user['last_seen'];
        }
        return $userArrayMoreInfo;
    }
}

function getParentName($parent_id){
    $sql_bring_parent = "SELECT `name` FROM users WHERE id = '$parent_id'";
    $result_bring = mysqli_query($GLOBALS['connectionSQL'],$sql_bring_parent);
    $parents = mysqli_fetch_all($result_bring,MYSQLI_ASSOC);
    foreach($parents as $parent)
    {
        $temp = $parent['name'];
    }
    return $temp;
}

function checkAccount(){

    $tempArray = getUserInfo($_SESSION['id']);
    if(empty($tempArray)){print '<div class="alert alert-danger text-center m-2"  role="alert">Error 753</div>'; die();}
    if(empty($tempArray['parent_id']))
    {
        if($tempArray['status'] == 1) return true;
        if($tempArray['status'] == 0) {print '<div class="alert alert-danger text-center m-2"  role="alert">This account has been disabled </div>'; logOut();}
    }
    else
    {
        $tempArray2 = getUserInfo($tempArray['parent_id']);
        if($tempArray2['status'] == 1 && $tempArray['status'] == 1) return true;
        if($tempArray2['status'] == 0) {print '<div class="alert alert-danger text-center m-2"  role="alert">This account has been disabled </div>'; logOut();}
    }


}

function getHash($password){
    return md5($password).sha1($password);
}

function logOut($reload=false,$dos=false){
    if($dos) deleteOldSession();

    $_SESSION['id'] = $_SESSION['language'] = $_SESSION['token'] ='';
    session_destroy();
    session_commit();

    if($reload) print "<script>location.href ='"._HOME_._DIR_FROM_ROOT_."login';</script>";
    die();
}

function NotAllowedEditAnythingForSuperAdmin($id){
    if ($id == 1) {
        print '<script> notice(\'error\',\'Not allowed\'); </script>';
        die();
    }
}

function sendEmail ($code,$email) {
    $to = $email;
    $subject = "Code for register a new account";

    $message = str_replace('{{CODE}}',$code,file_get_contents('pages/emailPage.php'));
// add more info
    /* $headers .= "Reply-To: The Sender <admin@domain.com>\r\n";
     $headers .= "Return-Path: The Sender <admin@domain.com>\r\n";
     $headers .= "From: sender@byrings.com" ."\r\n" .
         $headers .= "Organization: Sender Organization\r\n";
     $headers .= "MIME-Version: 1.0\r\n";
     $headers .= "Content-type: text/html; charset=utf-8\r\n";
     $headers .= "X-Priority: 3\r\n";
     $headers .= "X-Mailer: PHP". phpversion() ."\r\n" ;*/
    $headers = array(
        "MIME-Version" => "1.0",
        "Content-type" => "text/HTML; charset=UTF-8",
        "From" => "Byrings",
        "Reply-To" => "<haidarsa48@gmail.com>"
    );

    if (!mail($to, $subject, $message, $headers))
        if(!mail($to, $subject, $message, $headers))
            return false;
    return true;
}

function checkVerification() {
    $userInformation = getUserInfo($_SESSION['id']);

    if($userInformation['ncc']){
        $query = "SELECT * FROM `verifies` WHERE `user_id` =".$_SESSION['id'];
        $result = mysqli_query($GLOBALS['connectionSQL'], $query);
        $userVerification = mysqli_fetch_all($result, MYSQLI_ASSOC)[0];

        if(!$userVerification['is_confirmed']){
            if(sendEmail(base64_decode($userVerification['code']),$userInformation['e-mail'])){


                print  '<div class="modal fade" id="confirmCode_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Confirm Code</h5>
                                    <div id="Notice__confirmCode" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                                    <h5>We are sent you code Please check your E-mail</h5><br>
                                    <form id="formConfirmCode">
                                        <input type="hidden" name="todo" id="todo" value="conCode">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="confirmCode" id="confirmCode" placeholder ="Confirm Code" >
                                            <label for="confirmCode"><h6>Confirm Code</h6></label>
                                        </div>
                                        <div class="modal-footer text-center">
                                            <button type="button"  class="btn btn-warning"  onclick="send_form(\'Notice__confirmCode\',\'formConfirmCode\')" >Send</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';
                print '<script>$(\'#confirmCode_modal\').modal(\'show\');</script>';
            }

        }
    }
}

//// =====>  End Function For User ////
/***************************************************************************************/


/***************************************************************************************/
//// =====>  Start Function For Input ////

function filterNumber($var){
    return filter_var($var,FILTER_SANITIZE_NUMBER_INT);
}

function filterString($var){return filter_var($var,FILTER_SANITIZE_STRING);}


//// =====>  End Function For Input ////
/***************************************************************************************/


/***************************************************************************************/
//// =====>  Start Function For Permissions ////

function permissionsMatrix($login){
    $sql = "SELECT * FROM `just_perm_name` WHERE `login` = '$login'";
    $result = mysqli_query($GLOBALS['connectionSQL'],$sql);
    return mysqli_fetch_all($result,MYSQLI_ASSOC);
}

function getUserPermissions($userId){
    if(empty($userId)) return;
    $sql = "SELECT `permission` FROM `permissions` WHERE `user_id` = ".$userId;
    $result = mysqli_query($GLOBALS['connectionSQL'],$sql);
    $permissions =  mysqli_fetch_all($result,MYSQLI_ASSOC);

    $permissionsArray = array();
    if($permissions)
    {
        foreach ($permissions as $permission){
            $permissionsArray[$permission['permission']] = 1;
        }
    }
    return $permissionsArray;
}

//// =====>  End Function For Permissions ////
/***************************************************************************************/


/***************************************************************************************/
//// =====>  Start Function For Session ////

function getUserToken()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $ip = null;
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip . ":" . $user_agent;;
}

function creatNewSession($userId){
    $date = date('Y-m-d h:i:s');
    $ip = get__ip();
    $device = get__device();
    $os = get__os();
    $browser = get__browser();

    $sid = session_id();

    $query = "INSERT INTO `sessions`(`user_id`,`device`,`os`, `browser`, `ip`, `sid`,`last_seen`) VALUES ('$userId','$device','$os','$browser','$ip','$sid','$date')";
    mysqli_query($GLOBALS['connectionSQL'], $query);
}

function getSessionInfo($userId){

    $query = "SELECT * FROM `sessions` WHERE `user_id` = ".$userId;
    $result = mysqli_query($GLOBALS['connectionSQL'],$query);
    $sessions = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $sessionArrayInfo = array();

    if($sessions)
    {
        foreach ($sessions as $session)
        {
            $sessionArrayInfo['id']          = $session['id'];
            $sessionArrayInfo['user_id']     = $session['user_id'];
            $sessionArrayInfo['device']     = $session['device'];
            $sessionArrayInfo['os']          = $session['os'];
            $sessionArrayInfo['browser']     = $session['browser'];
            $sessionArrayInfo['ip']          = $session['ip'];
            $sessionArrayInfo['sid']         = $session['sid'];
            $sessionArrayInfo['last_seen']   = $session['last_seen'];

        }
    }
    return $sessionArrayInfo;
}

function UpdateThisSession($userId){
    $date = date('Y-m-d h:i:s');
    $ip = get__ip();
    $device = get__device();
    $os = get__os();
    $browser = get__browser();

    $sid = session_id();

    $query = "UPDATE `sessions` SET `device`='$device',`os`='$os',`browser`='$browser',`ip`='$ip',`sid`='$sid',`last_seen`='$date' WHERE `user_id`=".$userId;
    mysqli_query($GLOBALS['connectionSQL'], $query);
}

function deleteOldSession(){
    $date = date('Y-m-d h:i:s');
    $query = "UPDATE `sessions` SET `last_seen` = '$date' , `sid` = '-----' WHERE `sessions`.`user_id` =  ".$_SESSION['id'];
    mysqli_query($GLOBALS['connectionSQL'],$query);
}

function checkSession(){
    if(!password_verify(getUserToken(),$_SESSION['token'])) logOut(true);

    if(getSessionInfo($_SESSION['id'])['sid'] != session_id())
    {
        logOut(true);
    }
    else
    {
        updateLastSeen();
    }
}

function updateLastSeen(){
    $userId = $_SESSION['id'];
    $date = date('Y-m-d h:i:s');
    $query = "UPDATE `sessions` SET `last_seen` = '$date' WHERE `sessions`.`user_id` =  ".$userId;
    mysqli_query($GLOBALS['connectionSQL'],$query);
}

//// =====>  End Function For Session ////
/***************************************************************************************/


/***************************************************************************************/
//// =====>  Start Function For User Connection ////

function get__ip() {
    $mainIp = '';
    if (getenv('HTTP_CLIENT_IP'))
        $mainIp = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $mainIp = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $mainIp = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $mainIp = getenv('REMOTE_ADDR');
    else
        $mainIp = 'UNKNOWN';
    return $mainIp;
}

function get__os() {

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform    =   "Unknown OS Platform";
    $os_array       =   array(
        '/windows nt 11/i'     	=>  'Windows 10',
        '/windows nt 10/i'     	=>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }
    }
    return $os_platform;
}

function get__browser() {

    $browser        =   'Unknown Browser';

    $browser_array  =   array(
        '/msie/i'       =>  'Internet Explorer',
        '/Trident/i'    =>  'Internet Explorer',
        '/firefox/i'    =>  'Firefox',
        '/safari/i'     =>  'Safari',
        '/chrome/i'     =>  'Chrome',
        '/edge/i'       =>  'Edge',
        '/opera/i'      =>  'Opera',
        '/netscape/i'   =>  'Netscape',
        '/maxthon/i'    =>  'Maxthon',
        '/konqueror/i'  =>  'Konqueror',
        '/ubrowser/i'   =>  'UC Browser',
        '/mobile/i'     =>  'Handheld Browser'
    );

    foreach ($browser_array as $regex => $value) {

        if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) {
            $browser    =   $value;
        }

    }

    return $browser;

}

function get__device(){

    $tablet_browser = 0;
    $mobile_browser = 0;

    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $tablet_browser++;
    }

    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $mobile_browser++;
    }

    if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
        $mobile_browser++;
    }

    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    $mobile_agents = array(
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
        'newt','noki','palm','pana','pant','phil','play','port','prox',
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
        'wapr','webc','winw','winw','xda ','xda-');

    if (in_array($mobile_ua,$mobile_agents)) {
        $mobile_browser++;
    }

    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
        $mobile_browser++;
        //Check for tablets on opera mini alternative headers
        $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
            $tablet_browser++;
        }
    }

    if ($tablet_browser > 0) {
        // do something for tablet devices
        return 'Tablet';
    }
    else if ($mobile_browser > 0) {
        // do something for mobile devices
        return 'Mobile';
    }
    else {
        // do something for everything else
        return 'Computer';
    }
}

//// =====>  End Function For User Connection ////
/***************************************************************************************/

?>