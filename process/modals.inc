<?php
if(isset($_POST['getModalForUserTool'])){

    $id = $_POST['userId_EF'];

    /** Not Allowed Edit Anything for SUPER admin **/
    NotAllowedEditAnythingForSuperAdmin($id);

    $user = getUserInfo($id);

    /***  Start Validate  ***/
    if(empty($_POST['userId_EF'])) die();
    if($user['login'] == 'SUPER admin') die();
    if($_SESSION['id'] == $id) die();
    if(getUserInfo($_SESSION['id'])['login'] == 'user' && $user['login'] == 'admin') die();
    if(getUserInfo($_SESSION['id'])['login'] == 'user' && $user['login'] == 'user') die();
    /***  End validate  ***/

    /*** Start Validate for Edit Information User ***/
    if(getUserInfo($_SESSION['id'])['login'] == 'admin' && getUserPermissions($_SESSION['id'])['Edit_Information_Admin'] && getUserInfo($id)['login'] == 'admin'
        || getUserInfo($_SESSION['id'])['login'] == 'admin' && getUserPermissions($_SESSION['id'])['Edit_Information_User'] && getUserInfo($id)['login'] == 'user'
        || getUserInfo($_SESSION['id'])['login'] == 'SUPER admin'
        /**ADD**/ || getUserInfo($_SESSION['id'])['login'] == 'user' && getUserInfo($id)['login'] == 'subordinate')
    {
        /***  Languages  ***/
        if($user['language'] == 'en')
            $language = str_replace(['{{selected_en}}','{{selected_ar}}'],['selected',''],$language__Template__);
        else
            $language = str_replace(['{{selected_en}}','{{selected_ar}}'],['','selected'],$language__Template__);

        $cardEditInformation = ' <div class="card collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fa-solid fa-user-pen"></i> '.getLanguage('Edit Information').'</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                                    <i class="fa-solid fa-plus text-danger"></i></button>                                               
                                            </div>
                                        </div>
                                        <div class="card-body" style="display: none;">
                                          <form id="formEditUser_'.$id.'">
                                        
                                            <input type="hidden" name="todo" id="todo" value="editUserInfo">
                                            <input type="hidden" name="userId" id="userId" value="'.$user['id'].'">
                                            
                                            <dev class="row">
                                                 <div class="form-floating mb-3 col-xl-6">
                                                    <input type="text" class="form-control" name="fullName" id="fullName" placeholder ="" value="'.$user['name'].'">
                                                    <label for="fullName"><h6>'.getLanguage('Full Name').'</h6></label>
                                                </div>
                                                
                                                <div class="form-floating mb-3 col-xl-6">
                                                    <input type="text" class="form-control" name="email" id="email" placeholder ="'.getLanguage('E-mail').'" value="'.$user['e-mail'].'">
                                                    <label for="fullName"><h6>'.getLanguage('E-mail').'</h6></label>
                                                </div>
                                                
                                                <div class="form-floating mb-3 col-xl-6">
                                                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder ="'.getLanguage('Mobile').'" value="'.$user['mobile'].'">
                                                    <label for="fullName"><h6>'.getLanguage('Mobile').'</h6></label>
                                                </div>
                                                
                                                <div class="form-floating mb-3 col-xl-6">
                                                    <input type="text" class="form-control" name="address" id="address" placeholder ="" value="'.$user['address'].'">
                                                    <label for="fullName"><h6>'.getLanguage('Address').'</h6></label>
                                                </div>
                                                
                                                '.$language.'                                                                                                                         
                                              
                                            </dev><br>
                                            <div class="modal-footer">
                                                <button type="button"  class="btn btn-warning"  onclick="send_form(\'errorNotice_'.$id.'\',\'formEditUser_'.$id.'\')">'.getLanguage('Save').'</button>
                                            </div>
                                        </form>
                                        </div>
                                     </div>';
    }else $cardEditInformation = '';
    /*** End Validate for Edit Information User ***/


    /*** Start Validate for Control Status Account ***/
    if(getUserInfo($_SESSION['id'])['login'] == 'SUPER admin'
        || getUserPermissions($_SESSION['id'])['Control_Status_Account_Admin'] && getUserInfo($id)['login'] == 'admin'
        || getUserPermissions($_SESSION['id'])['Control_Status_Account_User'] && getUserInfo($id)['login'] == 'user'
        /**ADD**/ || getUserInfo($_SESSION['id'])['login'] == 'user' && getUserInfo($id)['login'] == 'subordinate') {

        /***  Account Status  ***/
        if($user['status'] == '1')
            $accountStatus = str_replace(['{{selected_1}}','{{selected_0}}'],['selected',''],$accountStatus__Template__);
        else
            $accountStatus = str_replace(['{{selected_1}}','{{selected_0}}'],['','selected'],$accountStatus__Template__);


        $cardControlStatus = '<div class="card collapsed-card ">
                                                <div class="card-header">
                                                    <h3 class="card-title"><i class="fa-solid fa-user-gear"></i> '.getLanguage('Control Status User').'</h3>
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                                            <i class="fa-solid fa-plus text-danger"></i></button> 
                                                    </div>
                                                </div>
                                                <div class="card-body text-left text-md" style="display: none;">                                               
                                                    <form id="formControlStatus_'.$id.'">
                                                        <input type="hidden" name="todo" id="todo" value="editControlStatus">
                                                        <input type="hidden" name="userId" id="userId" value="'.$id.'">
                                                        
                                                        <div class="container">
                                                                '.$accountStatus.'
                                                        </div>
                                                                                                                
                                                        <br>
                                                         <div class="modal-footer">
                                                            <button type="button"  class="btn btn-warning"  onclick="send_form(\'errorNotice_'.$id.'\',\'formControlStatus_'.$id.'\')">'.getLanguage('Save').'</button>
                                                         </div>
                                                    </form>
                                                </div>
                                          </div>';
    }else $cardControlStatus = '';

    /*** End Validate for Control Status Account ***/


    /*** Start Validate for Edit Password User ***/
    if(getUserInfo($_SESSION['id'])['login'] == 'admin' && getUserPermissions($_SESSION['id'])['Edit_Password_Admin'] && getUserInfo($id)['login'] == 'admin'
        || getUserInfo($_SESSION['id'])['login'] == 'admin' && getUserPermissions($_SESSION['id'])['Edit_Password_User'] && getUserInfo($id)['login'] == 'user'
        || getUserInfo($_SESSION['id'])['login'] == 'SUPER admin'
        /**ADD**/ || getUserInfo($_SESSION['id'])['login'] == 'user' && getUserInfo($id)['login'] == 'subordinate')
    {
        $cardEditPassword = ' <div class="card collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fa-solid fa-key"></i> '.getLanguage('Edit Password').'</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                                    <i class="fa-solid fa-plus text-danger"></i></button>                                              
                                            </div>
                                        </div>
                                        <div class="card-body" style="display: none;">
                                          <form id="formEditPassword_'.$id.'">
                                        
                                            <input type="hidden" name="todo" id="todo" value="editUserPass">
                                            <input type="hidden" name="userId" id="userId" value="'.$user['id'].'">
                                            
                                            <dev class="row">                                                
                                                <div class="form-floating mb-3 col-xl-6">
                                                    <input type="password" class="form-control" name="newPass" id="newPass" placeholder ="'.getLanguage('New Password').'" value="">
                                                    <label for="fullName"><h6>'.getLanguage('New Password').'</h6></label>
                                                </div>
                                                
                                                <div class="form-floating mb-3 col-xl-6">
                                                    <input type="password" class="form-control" name="conPass" id="conPass" placeholder ="'.getLanguage('Confirm Password').'" value="">
                                                    <label for="fullName"><h6>'.getLanguage('Confirm Password').'</h6></label>
                                                </div>     
                                            </dev>
                                            <br>
                                            <div class="modal-footer">
                                                <button type="button"  class="btn btn-warning"  onclick="send_form(\'errorNotice_'.$id.'\',\'formEditPassword_'.$id.'\')">'.getLanguage('Save').'</button>
                                            </div>
                                        </form>
                                        </div>
                                     </div>';
    }else $cardEditPassword = '';
    /*** End Validate for Edit Password User ***/


    /***  Permissions  ***/
    if(getUserInfo($_SESSION['id'])['login'] == 'SUPER admin'
        || getUserPermissions($_SESSION['id'])['Edit_Permission_Admin'] && getUserInfo($id)['login'] == 'admin'
        || getUserPermissions($_SESSION['id'])['Edit_Permission_User'] && getUserInfo($id)['login'] == 'user')
    {
        foreach (permissionsMatrix(getUserInfo($id)['login']) as $permission) {
            $P = $permission['code'];
            $query = "SELECT * FROM `permissions` WHERE `permission` = '$P' AND `user_id` = " . $id;
            $result = mysqli_query($GLOBALS['connectionSQL'], $query);

            if(empty($permission['need'])) $need = '';
            else $need = 'Need: '.$permission['need'];

            $permissions .= ' ';
            if (mysqli_fetch_all($result, MYSQLI_ASSOC)){
                $permissions .= str_replace(['{{PermissionCode}}','{{PermissionName}}','{{checked}}','{{Need}}'],[$permission['code'],$permission['name'],'checked',$need],$permission__Template__);
            }
            else {
                $permissions .= str_replace(['{{PermissionCode}}', '{{PermissionName}}', '{{checked}}', '{{Need}}'], [$permission['code'], $permission['name'], ' ', $need], $permission__Template__);
            }
        }
        $cardEditPermissions = '<div class="card collapsed-card ">
                                                <div class="card-header">
                                                    <h3 class="card-title"><i class="fa-solid fa-fingerprint"></i> '.getLanguage('Edit Permission').'</h3>
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                                            <i class="fa-solid fa-plus text-danger"></i></button> 
                                                    </div>
                                                </div>
                                                <div class="card-body text-left text-md" style="display: none;">                                                
                                                    <form id="formEditPermission_'.$id.'">
                                                        <input type="hidden" name="todo" id="todo" value="editUserPerm">
                                                        <input type="hidden" name="usrId_PE" id="usrId_PE" value="'.$id.'">

                                                        '.$permissions.'
    
                                                        <br>
                                                         <div class="modal-footer">
                                                            <button type="button"  class="btn btn-warning"  onclick="send_form(\'errorNotice_'.$id.'\',\'formEditPermission_'.$id.'\')">'.getLanguage('Save').'</button>
                                                         </div>
                                                    </form>
                                                </div>
                                          </div>';
    }else $cardEditPermissions = '';

    print '<div class="modal fade " id="toolForUser_modal_'.$id.'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel"><label class="text-cyan"> '.$user['name'].'</label></h5>
                                    <div id="errorNotice_'.$id.'" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                                <!-- strat card -->
                                 '.$cardEditInformation.'
                                 <!-- end card -->
                                 <!-- strat card -->
                                 '.$cardControlStatus.'
                                 <!-- end card -->
                                 <!-- start card -->
                                 '.$cardEditPassword.'
                                 <!-- end card -->
                                 <!-- start card -->                                  
                                 '.$cardEditPermissions.'
                                 <!-- end card -->
                                 <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                </div>
                            </div>
                        </div>
                    </div>';

    print '<script>$(\'#toolForUser_modal_'.$id.'\').modal(\'show\');</script>';
    unset($id);
}

if(isset($_POST['getModalForMoreInfo'])){

    $id = $_POST['userId_MI'];

    /** Not Allowed Edit Anything for SUPER admin **/
    NotAllowedEditAnythingForSuperAdmin($id);

    $user = getUserInfo($id);
    if($user){
        $cardShowInformationUser = '<div class="card collapsed-card ">
                                                <div class="card-header">
                                                    <h3 class="card-title">'.getLanguage('Information User').'</h3>
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                                            <i class="fa-solid fa-plus text-danger"></i></button> 
                                                    </div>
                                                </div>
                                                <div class="card-body text-left text-md" style="display: none;">
                                                    <dev class="row">
                                                     <!-- start card -->
                                                         <dev class="col-12"> 
                                                             <div class="info-box mb-2 bg-cyan">
                                                                  <span class="info-box-icon"><i class="fa-solid fa-at"></i></span>                               
                                                                  <div class="info-box-content">
                                                                    <span class="info-box-text text-start">'.getLanguage('E-mail').'</span>
                                                                    <span class="info-box-number text-start">'.$user['e-mail'].'</span>
                                                                  </div>                                            
                                                            </div>
                                                        </dev>
                                                        
                                                         <dev class="col-12"> 
                                                             <div class="info-box mb-2 bg-cyan">
                                                                  <span class="info-box-icon"><i class="fa-solid fa-phone"></i></span>                               
                                                                  <div class="info-box-content">
                                                                    <span class="info-box-text text-start">'.getLanguage('Mobile').'</span>
                                                                    <span class="info-box-number text-start">'.$user['mobile'].'</span>
                                                                  </div>                                            
                                                            </div>
                                                        </dev>
                                                        
                                                         <dev class="col-12"> 
                                                             <div class="info-box mb-2 bg-cyan">
                                                                  <span class="info-box-icon"><i class="fa-solid fa-map-location-dot"></i></span>                               
                                                                  <div class="info-box-content">
                                                                    <span class="info-box-text text-start">'.getLanguage('Address').'</span>
                                                                    <span class="info-box-number text-start">'.$user['address'].'</span>
                                                                  </div>                                            
                                                            </div>
                                                        </dev>                                                        
                                                        
                                                         <dev class="col-12"> 
                                                             <div class="info-box mb-2 bg-cyan">
                                                                  <span class="info-box-icon"><i class="fa-solid fa-person-chalkboard"></i></span>                               
                                                                  <div class="info-box-content">
                                                                    <span class="info-box-text text-start">'.getLanguage('How Added').'</span>
                                                                    <span class="info-box-number text-start">'.$user['who_added'].'</span>
                                                                  </div>                                            
                                                            </div>
                                                        </dev>
                                                        
                                                        <dev class="col-12"> 
                                                             <div class="info-box mb-2 bg-cyan">
                                                                  <span class="info-box-icon"><i class="fa-regular fa-calendar-plus"></i></span>                               
                                                                  <div class="info-box-content">
                                                                    <span class="info-box-text text-start">'.getLanguage('Register Date').'</span>
                                                                    <span class="info-box-number text-start">'.$user['register_date'].'</span>
                                                                  </div>                                            
                                                            </div>
                                                        </dev>                                                           
                                                            
                                                    </dev>                                                   
                                                </div>
                                          </div>';
    }


    $user = getUserMoreInfo($id);
    if($user){
        $cardShowInformationConnection = '<div class="card collapsed-card ">
                                                <div class="card-header">
                                                    <h3 class="card-title">'.getLanguage('Information Connection').'</h3>
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                                            <i class="fa-solid fa-plus text-danger"></i></button> 
                                                    </div>
                                                </div>
                                                <div class="card-body text-left text-md" style="display: none;">
                                                
                                                   <dev class="row">
                                                     <!-- start card -->
                                                     <dev class="col-6"> 
                                                         <div class="info-box mb-2 bg-cyan">
                                                              <span class="info-box-icon"><i class="fa-solid fa-display"></i></span>                               
                                                              <div class="info-box-content">
                                                                <span class="info-box-text text-start">'.getLanguage('Device').'</span>
                                                                <span class="info-box-number text-start">'.$user['device'].'</span>
                                                              </div>                                            
                                                        </div>
                                                        </dev>
                                                     <!-- end card -->
                                                     <!-- start card -->
                                                     <dev class="col-6"> 
                                                         <div class="info-box mb-2 bg-cyan">
                                                              <span class="info-box-icon"><i class="<i fa-brands fa-windows"></i></span>                               
                                                              <div class="info-box-content">
                                                                <span class="info-box-text text-start">'.getLanguage('OS').'</span>
                                                                <span class="info-box-number text-start">'.$user['os'].'</span>
                                                              </div>                                            
                                                        </div>
                                                        </dev>
                                                     <!-- end card -->
                                                     </dev>
                                                     
                                                     <dev class="row">
                                                     <!-- start card -->
                                                     <dev class="col-6"> 
                                                         <div class="info-box mb-2 bg-cyan">
                                                              <span class="info-box-icon"><i class="fa-brands fa-internet-explorer"></i></span>                               
                                                              <div class="info-box-content">
                                                                <span class="info-box-text text-start">'.getLanguage('Browser').'</span>
                                                                <span class="info-box-number text-start" text-start>'.$user['browser'].'</span>
                                                              </div>                                            
                                                        </div>
                                                        </dev>
                                                     <!-- end card -->
                                                     
                                                     <!-- start card -->
                                                     <dev class="col-6"> 
                                                         <div class="info-box mb-2 bg-cyan">
                                                              <span class="info-box-icon"><i class="fa-solid fa-location-dot"></i></span>                               
                                                              <div class="info-box-content">
                                                                <span class="info-box-text text-start">'.getLanguage('IP').'</span>
                                                                <span class="info-box-number text-start">'.$user['ip'].'</span>
                                                              </div>                                            
                                                        </div></dev>
                                                        </dev>
                                                        <dev class="row">
                                                         <!-- end card -->
                                                         <!-- start card -->
                                                         <dev class="col-12">
                                                             <div class="info-box mb-2 bg-cyan col-12">
                                                                  <span class="info-box-icon"><i class="fas fa-clock"></i></span>                               
                                                                  <div class="info-box-content">
                                                                    <span class="info-box-text text-start">'.getLanguage('Last Seen').'</span>
                                                                    <span class="info-box-number text-start">'.$user['last_seen'].'</span>
                                                                  </div>                                            
                                                            </div>
                                                        </dev>
                                                         <!-- end card -->
                                                     </dev>                                                    
                                                </div>
                                          </div>';
    }
    print '<div class="modal fade " id="MoreInfoForUser_modal_'.$id.'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">'.getLanguage('More Information For:').' <label class="text-cyan">'.$user['name'].'</label></h5>
                                    </div>
                                    <div class="modal-body">
                                     '.$cardShowInformationUser.'
                                     '.$cardShowInformationConnection.'
                                     <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                    </div>
                                </div>
                            </div>
                        </div>';
    print '<script>$(\'#MoreInfoForUser_modal_'.$id.'\').modal(\'show\');</script>';
    unset($id);
//    print '<script> const element = document.getElementById("temp"); element.remove();</script>';
}

if(isset($_POST['getModalLogOut'])){

    /** create a modal */
    print  '<div class="modal fade" id="logout_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"  '.$dir.'>
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">'.getLanguage("Logout").'</h5>
                                </div>
                                <div class="modal-body">
                                    <form id="formLogOut">
                                        <input type="hidden" name="todo" id="todo" value="logout">
                                        <h5>'.getLanguage("note for accept").'</h5>
                                        <div class="modal-footer text-center">
                                            <button type="button"  class="btn btn-warning"  onclick="send_form(\'spaceNotice\',\'formLogOut\')" data-bs-dismiss="modal" >'.getLanguage("Logout").'</button>
                                            <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';
    print '<script>$(\'#logout_modal\').modal(\'show\');</script>';
//    print '<script> const element = document.getElementById("temp"); element.remove();</script>';
}

if(isset($_POST['getModalChangeLanguage'])){
    $id = $_SESSION['id'];

    /** Not Allowed Edit Anything for SUPER admin **/
//    NotAllowedEditAnythingForSuperAdmin($id);

    $userLanguage = getUserInfo($id)['language'];
    /***  Languages  ***/
    if($userLanguage == 'en')
        $languageSelect = str_replace(['{{selected_en}}','{{selected_ar}}'],['selected',''],$language__Template__);
    else
        $languageSelect = str_replace(['{{selected_en}}','{{selected_ar}}'],['','selected'],$language__Template__);
    /** create a modal */
    print  '<div class="modal fade" id="ChangeLanguage_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">'.getLanguage("Edit language").'</h5>
                                    <div id="Notice__ChangeLanguage" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                                    <form id="formChangeLanguage">
                                        <input type="hidden" name="todo" id="todo" value="ChangeLanguage">
                                        '.$languageSelect.'
                                        <div class="modal-footer text-center">
                                            <button type="button"  class="btn btn-warning"  onclick="send_form(\'Notice__ChangeLanguage\',\'formChangeLanguage\')" data-bs-dismiss="modal" >'.getLanguage("Save").'</button>
                                            <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';
    print '<script>$(\'#ChangeLanguage_modal\').modal(\'show\');</script>';
//    print '<script> const element = document.getElementById("temp"); element.remove();</script>';
}

if(isset($_POST['getModalChangePassword'])){
    $id = $_SESSION['id'];

    /** Not Allowed Edit Anything for SUPER admin **/
//    NotAllowedEditAnythingForSuperAdmin($id);

    /** create a modal */
    print  '<div class="modal fade" id="ChangePassword_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">'.getLanguage("Edit Password").'</h5>
                                    <div id="Notice__ChangePassword" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                                    <form id="formChangePassword">
                                        <input type="hidden" name="todo" id="todo" value="ChangePassword">
                                       <dev class="row">       
                                                <div class="form-floating mb-3 col-xl-12">
                                                    <input type="password" class="form-control" name="oldPass" id="oldPass" placeholder ="'.getLanguage('Old Password').'" value="">
                                                    <label for="fullName"><h6>'.getLanguage('Old Password').'</h6></label>
                                                </div>
                                                                                         
                                                <div class="form-floating mb-3 col-xl-12">
                                                    <input type="password" class="form-control" name="newPass" id="newPass" placeholder ="'.getLanguage('New Password').'" value="">
                                                    <label for="fullName"><h6>'.getLanguage('New Password').'</h6></label>
                                                </div>
                                                
                                                <div class="form-floating mb-3 col-xl-12">
                                                    <input type="password" class="form-control" name="conPass" id="conPass" placeholder ="'.getLanguage('Confirm Password').'" value="">
                                                    <label for="fullName"><h6>'.getLanguage('Confirm Password').'</h6></label>
                                                </div>     
                                            </dev>
                                        <div class="modal-footer text-center">
                                            <button type="button"  class="btn btn-warning"  onclick="send_form(\'Notice__ChangePassword\',\'formChangePassword\')"  >'.getLanguage("Save").'</button>
                                            <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';
    print '<script>$(\'#ChangePassword_modal\').modal(\'show\');</script>';
//    print '<script>out const element = document.getElementById("temp"); element.remove();</script>';
}

if(isset($_POST['getModalAddUser'])){
    $id = $_SESSION['id'];
    $userLogin = getUserInfo($id)['login'];

    if($userLogin == 'subordinate') die();
    if($userLogin == 'SUPER admin' || getUserPermissions($id)['Add_New_User'] && getUserPermissions($id)['Add_New_Admin'])
        $userIs = '<div class="container col-xl-12">
                        <div class="form-select form-select-sm mb-3 col-xl-12">
                            <label for="userIs">User is:</label>
                            <select id="userIs" name="userIs" class="form-select">
                                <option selected value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>';
    elseif (getUserPermissions($id)['Add_New_Admin']) $userIs = '<input type="hidden" name="userIs" id="userIs" value="admin">';
    elseif (getUserPermissions($id)['Add_New_User']) $userIs = '<input type="hidden" name="userIs" id="userIs" value="user">';
    elseif (getUserPermissions($id)['Add_New_subordinate']) $userIs = '<input type="hidden" name="userIs" id="userIs" value="subordinate">';

    print '<div class="modal fade " id="addUser_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Add User</h5>
                                    <div id="Notice__AddUser" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                                
                                 <form id="formAddUser">
                                       
                                    <input type="hidden" name="todo" id="todo" value="addUser">
                                                                                
                                    <dev class="row">
                                    
                                        '.$userIs.'
                                        
                                         <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="fullName" id="fullName" placeholder ="'.getLanguage('Full Name').'" required>
                                            <label for="fullName"><h6>'.getLanguage('Full Name').'</h6></label>
                                        </div>
                                        
                                        <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="username" id="username" placeholder ="" required>
                                            <label for="username"><h6>'.getLanguage('UserName').'</h6></label>
                                        </div>
                                        
                                        <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="email" id="email" placeholder ="'.getLanguage('E-mail').'" required>
                                            <label for="email"><h6>'.getLanguage('E-mail').'</h6></label>
                                        </div>
                                        
                                        <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder ="'.getLanguage('Mobile').'" required>
                                            <label for="mobile"><h6>'.getLanguage('Mobile').'</h6></label>
                                        </div>
                                        
                                        <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="address" id="address" placeholder ="" required>
                                            <label for="address"><h6>'.getLanguage('Address').'</h6></label>
                                        </div>
                                        
                                        <div class="form-floating mb-3 col-xl-6">
                                            <input type="password" class="form-control" name="password" id="password" placeholder ="'.getLanguage('Password').'" required>
                                            <label for="password"><h6>'.getLanguage('Password').'</h6></label>
                                        </div>
                                        
                                        <div class="form-floating mb-3 col-xl-12">
                                            <input type="password" class="form-control" name="passwordCon" id="passwordCon" placeholder ="'.getLanguage('Confirm Password').'" required>
                                            <label for="passwordCon"><h6>'.getLanguage('Confirm Password').'</h6></label>
                                        </div>
                                          
                                        <div class="container col-xl-12">
                                            <div class="form-select form-select-sm mb-3 col-xl-12">
                                                <label for="myLang">'.getLanguage('Language').'</label>
                                                <select id="myLang" name="myLang" class="form-select">
                                                    <option value="en">English</option>
                                                    <option value="ar">العربية</option>
                                                </select>
                                            </div>
                                         </div>
                        
                                          <div></div>                                                                                                                     
                                     
                                    </dev>
                                            
                                    <br>
                                    
                                    <div class="modal-footer">
                                        <button type="button"  class="btn btn-warning"  onclick="send_form(\'Notice__AddUser\',\'formAddUser\')">'.getLanguage("Add").'</button>
                                        <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                    </div>
                                    
                                </form>
                                
                                </div>
                            </div>
                        </div>
                    </div>';

    print '<script>$(\'#addUser_modal\').modal(\'show\');</script>';
}

/** Not Work For Now **/
if(isset($_POST['getModalEditMyInfo'])){
    $id = $_SESSION['id'];

    print '<div class="modal fade " id="editMyInfo_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Edit My Information</h5>
                                    <div id="Notice__EditMyInfo"  class="text-center"></div>
                                </div>
                                <div class="modal-body">
                                
                                 <form id="formEditMyInfo">
                                       
                                    <input type="hidden" name="todo" id="todo" value="addUser">
                                                                                
                                    <dev class="row">
                                                                            
                                         <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="fullName" id="fullName" placeholder ="Full Name" required>
                                            <label for="fullName"><h6>Full Name</h6></label>
                                        </div>
                                        
                                        <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="username" id="username" placeholder ="@Username" required>
                                            <label for="username"><h6>@Username</h6></label>
                                        </div>
                                        
                                        <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="email" id="email" placeholder ="E-mail" required>
                                            <label for="email"><h6>E-mail</h6></label>
                                        </div>
                                        
                                        <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder ="Mobile" required>
                                            <label for="mobile"><h6>Mobile</h6></label>
                                        </div>
                                        
                                        <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="address" id="address" placeholder ="Address" required>
                                            <label for="address"><h6>Address</h6></label>
                                        </div>                                      
                                                                      
                                                                                              
                                    </dev>
                                            
                                    <br>
                                    
                                    <div class="modal-footer">
                                        <button type="button"  class="btn btn-warning"  onclick="send_form(\'Notice__EditMyInfo\',\'formEditMyInfo\')">'.getLanguage("Add").'</button>
                                        <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                    </div>
                                    
                                </form>
                                
                                </div>
                            </div>
                        </div>
                    </div>';

    print '<script>$(\'#editMyInfo_modal\').modal(\'show\');</script>';
}


