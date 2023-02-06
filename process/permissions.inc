<?php

/* validate is done */
if($_POST['todo'] == 'editUserPerm') {
    $userId = $_POST['usrId_PE'];

    /** Not Allowed Edit Anything for SUPER admin **/
    NotAllowedEditAnythingForSuperAdmin($userId);

    if (getUserInfo($_SESSION['id'])['login'] == 'SUPER admin'
        || getUserPermissions($_SESSION['id'])['Edit_Permission_Admin'] && getUserInfo($userId)['login'] == 'admin'
        || getUserPermissions($_SESSION['id'])['Edit_Permission_User'] && getUserInfo($userId)['login'] == 'user') {
        ///********   Permissions   ********///

        /// Admin
        $Show_All_Users = $_POST['Show_All_Users'];
        $Control_Status_Account_User = $_POST['Control_Status_Account_User'];
        $Control_Status_Account_Admin = $_POST['Control_Status_Account_Admin'];
        $Edit_Permission_Admin = $_POST['Edit_Permission_Admin'];
        $Edit_Permission_User = $_POST['Edit_Permission_User'];
        $Edit_Information_Admin = $_POST['Edit_Information_Admin'];
        $Edit_Information_User = $_POST['Edit_Information_User'];
        $Edit_Password_Admin = $_POST['Edit_Password_Admin'];
        $Edit_Password_User = $_POST['Edit_Password_User'];
        $Add_New_User = $_POST['Add_New_User'];
        $Add_New_Admin = $_POST['Add_New_Admin'];

        ///User
        $Add_New_subordinate = $_POST['Add_New_subordinate'];
        $Create_A_Restaurant = $_POST['Create_A_Restaurant'];

        ///********   Permissions   ********///

        $query = "DELETE FROM `permissions` WHERE `user_id` = " . $userId;
        mysqli_query($GLOBALS['connectionSQL'], $query);


///*********************************************************************************************************************
        if (!empty($Show_All_Users)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Show_All_Users',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        if (!empty($Control_Status_Account_User)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Control_Status_Account_User',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        if (!empty($Control_Status_Account_Admin)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Control_Status_Account_Admin',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        if (!empty($Edit_Permission_Admin)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Edit_Permission_Admin',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        if (!empty($Edit_Permission_User)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Edit_Permission_User',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        if (!empty($Edit_Information_Admin)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Edit_Information_Admin',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        if (!empty($Edit_Information_User)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Edit_Information_User',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        if (!empty($Edit_Password_Admin)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Edit_Password_Admin',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        if (!empty($Edit_Password_User)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Edit_Password_User',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        if (!empty($Add_New_User)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Add_New_User',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        if (!empty($Add_New_Admin)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Add_New_Admin',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        if (!empty($Add_New_subordinate)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Add_New_subordinate',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        if (!empty($Create_A_Restaurant)) {
            $query = "INSERT INTO `permissions`(`permission`, `user_id`) VALUES ('$Create_A_Restaurant',$userId)";
            mysqli_query($GLOBALS['connectionSQL'], $query);
        }
///*********************************************************************************************************************
        print '<script> notice(\'success\',\'Done\'); </script>';
    } else {
        print '<script> notice(\'error\',\'Not Allowed\'); </script>';
        die();
    }
}