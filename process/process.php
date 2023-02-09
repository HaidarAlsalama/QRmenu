<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') require 'pages/error.php';

$_SESSION['language'] == 'ar' ? $dir = 'dir = "rtl"' : $dir = 'dir = "ltr"';

if(!isset($_SESSION['thisRequest'])) $_SESSION['thisRequest'] = rand(100,1001120);

if(isset($_POST['requestRand'])){

    if($_POST['requestRand'] != $_SESSION['thisRequest']){
        print'<script>alert("The request cannot be sent more than once. The page will be reloaded after pressing OK")</script>';
        print '<script>location.reload()</script>';
        die();
    }
}

if(isset($_SESSION['id'])) {
    checkAccount();
    checkSession();
    updateLastSeen();
}

//print $_SESSION['thisRequest'];

/*************** *************** ****************/

$permission__Template__ = '<div class="form-check">
                  <input class="form-check-input" type="checkbox" id="{{PermissionCode}}" name="{{PermissionCode}}" value="{{PermissionCode}}" {{checked}}>
                  <label class="form-check-label" for="flexCheckDefault">
                    {{PermissionName}}
                    <i class="text-danger">{{Need}}</i>
                  </label>
              </div>';
/************ ************/
$language__Template__ = '<div class="container col-xl-12">
                            <div class="form-select form-select-sm mb-3 col-xl-12">
                                <label for="myLang">' . getLanguage('Language') . '</label>
                                <select id="myLang" name="myLang" class="form-select">
                                    <option {{selected_en}} value="en">English</option>
                                    <option {{selected_ar}} value="ar">العربية</option>
                                </select>
                            </div>
                        </div>';
/************ ************/
$accountStatus__Template__ = '<div class="container col-xl-12">
                        <div class="form-select form-select-sm mb-3 col-xl-12">
                                <label for="myLang">' . getLanguage('Account Status') . '</label>
                                <select id="status" name="status" class="form-select">
                                    <option {{selected_1}} value="1">' . getLanguage('Active') . '</option>
                                    <option {{selected_0}} value="0">' . getLanguage('Disable') . '</option>
                                </select>
                            </div>
                        </div>';
/*********** *************/


/*************** *************** ****************/

/***************************************************************************************/
//// =====>  Start process for users ////

if($_POST['todo'] == 'login'){
    $date = date('Y-m-d h:i:s');
    $username = base64_encode(trim($_POST['username']));
    $password  = trim($_POST['password']);

    if(empty($username) || empty($password))
    {
        print '<script> notice(\'warning\',\'Enter your username and password\'); </script>';
        die();
    }

    $hash = getHash($password);

    $query = "SELECT `id`,`language` FROM `users` WHERE `username` = '$username' AND `password` = '$hash' LIMIT 1";
    $result = mysqli_query($GLOBALS['connectionSQL'], $query);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if($users)
    {
        foreach ($users as $user) {
            $_SESSION['id']    = $user['id'];
            $_SESSION['language']    = $user['language'];
        }

        checkAccount();

        print '<div class="alert alert-success text-center m-2"  role="alert">Please Wait <div class="spinner-border spinner-border-sm"></div></div>';

        $query = "SELECT `id` FROM `sessions` WHERE `user_id` = ".$_SESSION['id'];
        $result = mysqli_query($GLOBALS['connectionSQL'],$query);
        if(mysqli_fetch_all($result, MYSQLI_ASSOC))
        {
            UpdateThisSession($_SESSION['id']);
        }
        else
        {
            creatNewSession($_SESSION['id']);
        }

        $_SESSION['token'] = password_hash(getUserToken(), PASSWORD_DEFAULT);

        print "<script>location.href ='"._HOME_._DIR_FROM_ROOT_."home';</script>";

    }
    else
    {
        print '<script> notice(\'error\',\'Please make sure the information is correct\'); </script>';
        die();
    }
}

if(isset($_POST['updateLastSeen'])){
    $id = $_SESSION['id'];
//    print date('Y-m-d h:i:s');
    if(!empty($id)){
        updateLastSeen();
        checkAccount();
        checkSession();
    }
    unset($id);
}

if($_POST['todo'] == 'ChangeLanguage'){
    $id = $_SESSION['id'];
    $language = $_POST['myLang'];

    $query = "UPDATE `users` SET `language`='$language' WHERE `id` = ".$id;

    if(mysqli_query($GLOBALS['connectionSQL'],$query)){
        print '<script> notice(\'success\',\'Done\'); </script>';
        $_SESSION['language'] = $language;
        print "<script>location.href ='"._DIR_FROM_ROOT_."settings';</script>";
    }
    else{
        print '<script> notice(\'error\',\'Error\'); </script>';
    }
    unset($id);
}

if($_POST['todo'] == 'ChangePassword'){
    $id = $_SESSION['id'];

    $old = $_POST['oldPass'];
    $new = $_POST['newPass'];
    $con = $_POST['conPass'];

    if(empty($old)||empty($new)||empty($con)) {
        print '<script> notice(\'warning\',\'All fields are required\'); </script>';
        die();
    }

    $hashOld = getHash($old);
    $hashNew = getHash($new);
    $hashCon = getHash($con);

    if(getUserInfo($id)['password'] == $hashOld){
        if($hashNew == $hashCon){

            $query = "UPDATE `users` SET `password`='$hashNew' WHERE `id` = ".$id;

            if(mysqli_query($GLOBALS['connectionSQL'],$query)){
                print '<script> notice(\'success\',\'Done\'); </script>';
                print "<script>$('#ChangePassword_modal').modal('hide');</script>";
            }
            else{
                print '<script> notice(\'error\',\'Error\'); </script>';
                die();
            }
        }
        else{
            print '<script> notice(\'error\',\'No match\'); </script>';
            die();
        }
    }
    else{
        print '<script> notice(\'error\',\'Old Password is wrong\'); </script>';
        die();
    }

    unset($id);
}

if ($_POST['todo'] == 'logout'){
    logOut(true,true);
}

/* validate is done */
if($_POST['todo'] == 'editUserInfo'){
    $userId = trim($_POST['userId']);

    /** Not Allowed Edit Anything for SUPER admin **/
    NotAllowedEditAnythingForSuperAdmin($userId);

    if(getUserInfo($_SESSION['id'])['login'] == 'admin' && getUserPermissions($_SESSION['id'])['Edit_Information_Admin'] && getUserInfo($userId)['login'] == 'admin'
        || getUserInfo($_SESSION['id'])['login'] == 'admin' && getUserPermissions($_SESSION['id'])['Edit_Information_User'] && getUserInfo($userId)['login'] == 'user'
        || getUserInfo($_SESSION['id'])['login'] == 'SUPER admin' )
    {
        $fullName = trim($_POST['fullName']);
        $email = trim($_POST['email']);
        $mobile = trim($_POST['mobile']);
        $myLang = trim($_POST['myLang']);
        $address = trim($_POST['address']);

        if(empty($userId) || empty($fullName) || empty($fullName) || empty($email) || empty($mobile) || empty($myLang) || empty($address))
        {
            print '<script> notice(\'warning\',\'All fields are required\'); </script>';
            die();
        }

        $query = "UPDATE `users` SET `name`='$fullName',`e-mail`='$email',`mobile`='$mobile',`language`='$myLang',`address`='$address' WHERE `id` = ".$userId;

        mysqli_query($GLOBALS['connectionSQL'],$query);

        print '<script> notice(\'success\',\'Done\'); </script>';
    }
    else
    {
        print '<script> notice(\'error\',\'Not Allowed\'); </script>';
        die();
    }
}

/* validate is done */
if($_POST['todo'] == 'editControlStatus'){
    $userId = trim($_POST['userId']);

    /** Not Allowed Edit Anything for SUPER admin **/
    NotAllowedEditAnythingForSuperAdmin($userId);

    if(getUserInfo($_SESSION['id'])['login'] == 'SUPER admin'
        || getUserPermissions($_SESSION['id'])['Control_Status_Account_Admin'] && getUserInfo($userId)['login'] == 'admin'
        || getUserPermissions($_SESSION['id'])['Control_Status_Account_User'] && getUserInfo($userId)['login'] == 'user')
    {
        $status = $_POST['status'];

        $query = "UPDATE `users` SET `status`='$status' WHERE `id` =  " . $userId;

        mysqli_query($GLOBALS['connectionSQL'], $query);

        print '<script> notice(\'success\',\'Done\'); </script>';
    }
    else
    {
        print '<script> notice(\'error\',\'Not Allowed\'); </script>';
        die();
    }
}

/* validate is done */
if($_POST['todo'] == 'editUserPass'){
    $userId = $_POST['userId'];

    /** Not Allowed Edit Anything for SUPER admin **/
    NotAllowedEditAnythingForSuperAdmin($userId);

    if(getUserInfo($_SESSION['id'])['login'] == 'admin' && getUserPermissions($_SESSION['id'])['Edit_Password_Admin'] && getUserInfo($userId)['login'] == 'admin'
        || getUserInfo($_SESSION['id'])['login'] == 'admin' && getUserPermissions($_SESSION['id'])['Edit_Password_User'] && getUserInfo($userId)['login'] == 'user'
        || getUserInfo($_SESSION['id'])['login'] == 'SUPER admin' )
    {
        $newPass = trim($_POST['newPass']);
        $conPass = trim($_POST['conPass']);

        if(empty($newPass) || empty($conPass)){
            print '<script> notice(\'warning\',\'All fields are required\'); </script>';
            die();
        }
        if($newPass == $conPass){
            $password = getHash($newPass);
            $query = "UPDATE `users` SET `password`='$password' WHERE `id` =".$userId;
            if(mysqli_query($GLOBALS['connectionSQL'], $query))
                print '<script> notice(\'success\',\'Done\'); </script>';
            else
                print '<script> notice(\'error\',\'Error\'); </script>';
        }
        else
            print '<script> notice(\'error\',\'No match\'); </script>';
    }
    else
    {
        print '<script> notice(\'error\',\'Not Allowed\'); </script>';
        die();
    }
}

if($_POST['todo'] == 'addUser'){
    $userIs = trim($_POST['userIs']);
    $fullName = trim($_POST['fullName']);
    $username = base64_encode(trim($_POST['username']));
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $address = trim($_POST['address']);
    $myLang = trim($_POST['myLang']);
    $pass = trim($_POST['password']);
    $passCon = trim($_POST['passwordCon']);

    $myPer = getUserPermissions($_SESSION['id']);
    $id = $_SESSION['id'];
    $date = date('Y-m-d h:i:s');

    if(empty($fullName) || empty($email) || empty($mobile) || empty($address) || empty($myLang) || empty($username) || empty($pass) || empty($passCon))
    {print '<script> notice(\'warning\',\'All fields are required\'); </script>'; die();}

    $hash = getHash($pass);
    $hashCon = getHash($passCon);

    if($hash != $hashCon) {print '<script> notice(\'warning\',\'Password Does Not Match\'); </script>'; die();}

    if($userIs =='subordinate') $parentId = $_SESSION['id'];

    /* validate permission */
    if(getUserInfo($_SESSION['id'])['login'] != 'SUPER admin'){
        if(!$myPer['Add_New_Admin'] && $userIs == 'admin') {print '<script> notice(\'error\',\'Not Allowed Add Admin\'); </script>'; die();}
        if(!$myPer['Add_New_User'] && $userIs == 'user') {print '<script> notice(\'error\',\'Not Allowed Add User\'); </script>'; die();}
        if(!$myPer['Add_New_subordinate'] && $userIs == 'subordinate') {print '<script> notice(\'error\',\'Not Allowed Add subordinate\'); </script>'; die();}
    }


    if($userIs == 'subordinate'){
        $parentId = $_SESSION['id'];
        $queryInsert = "INSERT INTO `users`(`login`, `name`, `username`, `e-mail`, `mobile`, `password`, `language`, `parent_id`, `who_added`, `address`, `register_date`) 
                VALUES ('$userIs','$fullName','$username','$email','$mobile','$hash','$myLang',".$parentId.",".$id.",'$address','$date')";
    }
    else {
        $queryInsert = "INSERT INTO `users`(`login`, `name`, `username`, `e-mail`, `mobile`, `password`, `language`,`who_added`, `address`, `register_date`) 
                VALUES ('$userIs','$fullName','$username','$email','$mobile','$hash','$myLang'," . $id . ",'$address','$date')";
    }


    $query = "SELECT `id` FROM `users` WHERE `username` = '$username'";
    $result = mysqli_query($GLOBALS['connectionSQL'],$query);
    if(mysqli_fetch_all($result, MYSQLI_ASSOC)) { print '<script> notice(\'error\',\'UserName is not available\'); </script>'; die(); }

    $query = "SELECT `id` FROM `users` WHERE `e-mail` = '$email'";
    $result = mysqli_query($GLOBALS['connectionSQL'],$query);
    if(mysqli_fetch_all($result, MYSQLI_ASSOC)) { print '<script> notice(\'error\',\'E-mail is not available\'); </script>'; die(); }

    $query = "SELECT `id` FROM `users` WHERE `mobile` = '$mobile'";
    $result = mysqli_query($GLOBALS['connectionSQL'],$query);
    if(mysqli_fetch_all($result, MYSQLI_ASSOC)) { print '<script> notice(\'error\',\'Mobile number is not available\'); </script>'; die(); }

    if(mysqli_query($GLOBALS['connectionSQL'], $queryInsert))
    {
        print '<script> notice(\'success\',\'Done\'); </script>';
        print '<script>$(\'#addUser_modal\').modal(\'hide\');</script>';
    }

}

if($_POST['todo'] == 'registerNewUser') {

    $userIs = 'user';
    $fullName = trim($_POST['fullName']);
    $username = base64_encode(trim($_POST['username']));
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $address = trim($_POST['address']);
    $myLang = trim($_POST['myLang']);
    $pass = trim($_POST['password']);
    $passCon = trim($_POST['passwordCon']);

    $id = 1;

    $date = date('Y-m-d h:i:s');

    if(empty($fullName) || empty($email) || empty($mobile) || empty($address) || empty($myLang) || empty($username) || empty($pass) || empty($passCon))
    {print '<script> notice(\'warning\',\'All fields are required\'); </script>'; die();}

    $hash = getHash($pass);
    $hashCon = getHash($passCon);

    if($hash != $hashCon) {print '<script> notice(\'warning\',\'Password Does Not Match\'); </script>'; die();}

    $query = "SELECT `id` FROM `users` WHERE `username` = '$username'";
    $result = mysqli_query($GLOBALS['connectionSQL'],$query);
    if(mysqli_fetch_all($result, MYSQLI_ASSOC)) { print '<script> notice(\'error\',\'UserName is not available\'); </script>'; die(); }

    $query = "SELECT `id` FROM `users` WHERE `e-mail` = '$email'";
    $result = mysqli_query($GLOBALS['connectionSQL'],$query);
    if(mysqli_fetch_all($result, MYSQLI_ASSOC)) { print '<script> notice(\'error\',\'E-mail is not available\'); </script>'; die(); }

    $query = "SELECT `id` FROM `users` WHERE `mobile` = '$mobile'";
    $result = mysqli_query($GLOBALS['connectionSQL'],$query);
    if(mysqli_fetch_all($result, MYSQLI_ASSOC)) { print '<script> notice(\'error\',\'Mobile number is not available\'); </script>'; die(); }

    $queryInsert = "INSERT INTO `users`(`login`, `name`, `username`, `e-mail`, `mobile`, `password`, `language`,`who_added`, `address`, `register_date`,`ncc`) 
                VALUES ('$userIs','$fullName','$username','$email','$mobile','$hash','$myLang'," . $id . ",'$address','$date',1)";
    if(mysqli_query($GLOBALS['connectionSQL'], $queryInsert))
    {
        $codeRegister = rand(111111,999999);
        $idUserRegister = getUserInfo($username)['id'];
        $query = "INSERT INTO `verifies`(`user_id`, `code`) VALUES ($idUserRegister,$codeRegister)";

        if(mysqli_query($GLOBALS['connectionSQL'], $query)) {
            if(sendEmail($codeRegister,$email)) {
                print '<script> notice(\'success\',\'We are sent you code Please check your E-mail\'); </script>';

            }else print '<script> notice(\'error\',\'Error 547\'); </script>';


        }

    }

    print $queryInsert;

}

//// =====>  End process for users ////
/***************************************************************************************/


/***************************************************************************************/
//// =====>  Start Edit Permissions ////

require_once 'permissions.inc';

//// =====>  End Edit Permissions ////
/***************************************************************************************/


/***************************************************************************************/
//// =====>  Start Get Modals ////

require_once 'modals.inc';

//// =====>  End Get Modals ////
/***************************************************************************************/



/***************************************************************************************/
//// =====>  Start process for users ////

require_once 'forRestaurants.inc';

//// =====>  End process for users ////
/***************************************************************************************/

//unset($_SESSION['thisRequest']);

/**/

//    print '<script> alert("'.$new.'");</script>';
//    print '<script> $("#u_tr_'.$deleteUserId.'").remove();</script>';
//    print '<script> alert("'.$name.' | '.$userName.' | '.$password.' | '.$conPassword.'");</script>';
//    print "<script>setTimeout(function(){location.href ='pages/main';},1500);</script>";
//    print "<script>setTimeout(function(){location.reload();},1500);</script>";

//     **********   SHOW $ HIDE Modal    **********
//    print "<script>$('#modalChangeItem').modal('hide');</script>";
//    print '<script>$(\'#logout_modal\').modal(\'show\');</script>';

//     **********   DELETE ELEMENT FROM HTML    **********
//print '<script> const element = document.getElementById("temp"); element.remove();</script>';

?>