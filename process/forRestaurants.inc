<?php
require_once 'functionsApp.inc';

if(isset($_POST['getModalAddRestaurant'])){
$id = $_SESSION['id'];
$userLogin = getUserInfo($id)['login'];

if($userLogin == 'subordinate') die();

print '<div class="modal fade " id="addRestaurant_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Add Restaurant</h5>
                                    <div id="Notice__AddRestaurant" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                                
                                 <form id="formAddRestaurant">
                                       
                                    <input type="hidden" name="todo" id="todo" value="addRestaurant">
                                    <input type="hidden" name="requestRand" id="requestRand" value="'.$_SESSION['thisRequest'].'">
                                                                              
                                    <dev class="row">
                                       
                                         <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="restaurantName" id="restaurantName" placeholder ="Restaurant Name" required>
                                            <label for="fullName"><h6>Restaurant Name</h6></label>
                                        </div>
                                        

                                        <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control rsFocus" name="restaurantSpecial" id="restaurantSpecial" placeholder ="Restaurant Name Special" required>
                                            <label for="fullSpecial"><h6>Restaurant Name Special</h6></label>
                                            <div class="text-center text-danger text-xs ml-2 mr-2">
                                                <h6 class="rsNote d-none"> يجب ان يكون الاسم اعلاه مميز يرجى مراعاة شكله لانه سيظهر في الرابط والتقيد باللغة الانكليزية</h6>
                                            </div>
                                        </div>
                                         <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="location" id="location" placeholder ="Location" required>
                                            <label for="fullName"><h6>Location</h6></label>
                                        </div>
                                        
                                         <div class="form-floating mb-3 col-xl-6">
                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder ="Mobile" required>
                                            <label for="fullName"><h6>Mobile</h6></label>
                                        </div>                                                                                                            
                                    
                                    </dev>
                                            
                                    <br>
                                    
                                    <div class="modal-footer">
                                        <button type="button"  class="btn btn-warning"  onclick="send_form(\'Notice__AddRestaurant\',\'formAddRestaurant\')">'.getLanguage("Add").'</button>
                                        <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                    </div>
                                    
                                </form>
                                
                                </div>
                            </div>
                        </div>
                    </div>';

    print '<script>$(\'#addRestaurant_modal\').modal(\'show\');</script>';
    print "<script>document.querySelector('.rsFocus').onfocus = function() { document.querySelector('.rsNote').className = 'rsNote p-1'; };</script>";
    print "<script>document.querySelector('.rsFocus').onblur  = function() { document.querySelector('.rsNote').className = 'rsNote p-1 d-none'; };</script>";
}

if($_POST['todo'] == 'addRestaurant'){
    $restaurantName = trim($_POST['restaurantName']);
    $restaurantSpecial = str_replace(' ','_',trim($_POST['restaurantSpecial']));
    $location = trim($_POST['location']);
    $mobile = trim($_POST['mobile']);

    $id = $_SESSION['id'];
    $myPer = getUserPermissions($id);
    $myInfo = getUserInfo($id);


    if(empty($restaurantName) || empty($location) || empty($mobile) || empty($restaurantSpecial))
    {print '<script> notice(\'warning\',\'All fields are required\'); </script>'; die();}



    if($myInfo['login'] =='subordinate') die();
    if(!$myPer['Create_A_Restaurant']) { print '<script> notice(\'error\',\'Not allowed\'); </script>'; die();}

    $query = "SELECT `id` FROM `restaurants` WHERE `nameSpecial` = '$restaurantSpecial'";
    $result = mysqli_query($GLOBALS['connectionSQL'], $query);
    $restSpecial = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if(!empty($restSpecial))
    {
        print '<script> notice(\'warning\',\'Special Name is existing\'); </script>';
        die();
    }

    $query = "INSERT INTO `restaurants`(`name`, `nameSpecial` , `user_id`, `location`, `mobile`) 
                                    VALUES ('$restaurantName','$restaurantSpecial','$id','$location','$mobile')";

    if(mysqli_query($GLOBALS['connectionSQL'], $query))
    {
        unset($_SESSION['thisRequest']);
        print '<script> notice(\'success\',\'Done\'); </script>';
        print '<script>$(\'#addRestaurant_modal\').modal(\'hide\');</script>';

    }else{ print '<script> notice(\'error\',\'Error\'); </script>'; die();}

}

if(isset($_POST['getModalAddCategory'])){

    print '<div class="modal fade" id="addCategory_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" '.$dir.'>
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">'.getLanguage('Add Category').'</h5>
                                     <div id="Notice__AddCategory" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                               
                                 <form id="formAddCategory">
                                       
                                    <input type="hidden" name="todo" id="todo" value="addCategory">
                                    <input type="hidden" name="requestRand" id="requestRand" value="'.$_SESSION['thisRequest'].'">
                                    <input type="hidden" name="restaurantId" id="restaurantId" value="'.$_GET['all'][2 - _HOST_].'">                                         
                                                                                                                       
                                    <div class="">
                                       <label for="categoryName" class="mb-0"><h6>'.getLanguage('Name').'</h6></label>
                                       <input type="text" class="form-control mb-3" name="categoryName" id="categoryName" required>                                                                                                 
                                    </div>
                                                                                
                                    <div class="modal-footer">
                                        <button type="button"  class="btn btn-warning"  onclick="send_form(\'Notice__AddCategory\',\'formAddCategory\')">'.getLanguage("Add").'</button>
                                        <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                    </div>
                                    
                                </form>
                                
                                </div>
                            </div>
                        </div>
                    </div>';

    print '<script>$(\'#addCategory_modal\').modal(\'show\');</script>';
}

if($_POST['todo'] == 'addCategory'){
    $categoryName = trim($_POST['categoryName']);
    $restaurantId = $_SESSION['restaurant_id'];

    if(!isset($_SESSION['restaurant_id'])) { print '<script> notice(\'error\',\'Error not set RI\'); </script>'; die(); }
    if(empty(($categoryName))) { print '<script> notice(\'warning\',\'This field is required\'); </script>'; die(); }

    $query = "INSERT INTO `categorys`(`name`, `restaurant_id`) VALUES ('$categoryName','$restaurantId')";
    if(mysqli_query($GLOBALS['connectionSQL'], $query))
    {
        unset($_SESSION['thisRequest']);
        print '<script> notice(\'success\',\'Done\'); </script>';
        print '<script>$(\'#addCategory_modal\').modal(\'hide\');</script>';
        print '<script> location.reload(); </script>';
    }
}

if(isset($_POST['getModalAddItem'])){

    $restaurantId = $_SESSION['restaurant_id'];

    $query = "SELECT * FROM `categorys` WHERE `restaurant_id` = ".$restaurantId;
    $result = mysqli_query($GLOBALS['connectionSQL'], $query);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $allCategory = '';
    foreach ($categories as $category){
        $allCategory .= '<option  value="'.$category['id'].'">'.$category['name'].'</option>';
    }

    print '<div class="modal fade" id="addItem_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" '.$dir.'>
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">'.getLanguage('Add Item').'</h5>
                                    <div id="Notice__AddItem" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                                
                                 <form id="formAddItem" enctype="multipart/form-data">
                                       
                                    <input type="hidden" name="todo" id="todo" value="addItem">
                                    <input type="hidden" name="requestRand" id="requestRand" value="'.$_SESSION['thisRequest'].'">
                                                                                
                                    <dev class="">
                                        <div class="form-control-border mb-3">
                                           <label for="exampleDataList" class="form-label mb-0"><h6>'.getLanguage('Select Category').'</h6></label>
                                            <input class="form-control" list="datalistOptions" id="categoryId" name="categoryId" placeholder="'.getLanguage('Search').'">
                                            <datalist id="datalistOptions">                                              
                                              '.$allCategory.'
                                            </datalist>
                                        </div>
                                        
                                         <div class="mb-3">
                                            <label for="itemName" class="mb-0"><h6>'.getLanguage('Item Name').'</h6></label>
                                            <input type="text" class="form-control" name="itemName" id="itemName" required>                                           
                                         </div>
                                         
                                         <div class="mb-3">
                                            <label for="itemDetails" class="mb-0"><h6>'.getLanguage('Item Details').'</h6></label>
                                            <input type="text" class="form-control" name="itemDetails" id="itemDetails"">                                            
                                         </div>
                                         
                                         <div class="mb-3">
                                            <label for="itemPrice" class="mb-0"><h6>'.getLanguage('Item Price').'</h6></label>
                                            <input type="number" class="form-control" name="itemPrice" id="itemPrice" required>                                            
                                         </div>
                                        
                                       <div>
                                          <label for="itemImage" class="form-label mb-0"><h6>'.getLanguage('Select Image').'</h6></label>
                                          <input class="form-control form-control"  name="itemImage" id="itemImage" type="file">
                                        </div>                                                                                              
                                        
                                    </dev>
                                                                                
                                    <div class="modal-footer">
                                        <button type="button"  class="btn btn-warning"  onclick="send_form(\'Notice__AddItem\',\'formAddItem\')">'.getLanguage("Add").'</button>
                                        <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                    </div>
                                    
                                </form>
                                
                                </div>
                            </div>
                        </div>
                    </div>';

    print '<script>$(\'#addItem_modal\').modal(\'show\');</script>';
}

if($_POST['todo'] == 'addItem'){

    $categoryId = trim($_POST['categoryId']);
    $restaurantId = $_SESSION['restaurant_id'];
    $itemName = trim($_POST['itemName']);
    $itemDetails = trim($_POST['itemDetails']);
    $itemPrice = trim($_POST['itemPrice']);
    $image = $_FILES['itemImage'];

    if(!isset($_SESSION['restaurant_id'])) { print '<script> notice(\'error\',\'Error not set RI\'); </script>'; die(); }
    if(empty(($itemPrice)) || empty(($itemName)) || empty(($categoryId)) ) { print '<script> notice(\'warning\',\'This field is required\'); </script>'; die(); }

    $imageNewName = uploadImage($image,'menu');

    $query = "INSERT INTO `items`(`category_id`, `name`, `details`, `price`,`photo`,`restaurant_id`)
                                VALUES ('$categoryId','$itemName','$itemDetails','$itemPrice','$imageNewName','$restaurantId')";
    if(mysqli_query($GLOBALS['connectionSQL'], $query))
    {
        unset($_SESSION['thisRequest']);
        print '<script> notice(\'success\',\'Done\'); </script>';
        print '<script>$(\'#addItemForThisCategory_modal\').modal(\'hide\');</script>';


        $html = file_get_contents(_STYLE_.'myMenu.rings');
        $str2f = "/<!-- ITEM_WITHOUT_RELOAD_RTL_START -->(.*?)<!-- ITEM_WITHOUT_RELOAD_RTL_END -->/s";
        preg_match($str2f,$html,$item_temp_rtl);
        if(empty($imageNewName))
            $photo = _HOME_._DIR_FROM_ROOT_.'img/menu/rings.jpg';
        else
            $photo = _HOME_._DIR_FROM_ROOT_.'img/menu/'.$imageNewName;
        $contentTemp .= str_replace(['{{NAME}}','{{IMAGE}}','{{DETAILS}}','{{PRICE}}'],
            [$itemName,$photo,$itemDetails,$itemPrice,],$item_temp_rtl[0]);

        print '<script>addItemForCategory("'.$categoryId.'","'.$contentTemp.'");</script>';
    } else { print '<script> notice(\'warning\',\'No Category Selected\'); </script>'; die(); }
}

if(isset($_POST['getModalAddItemForThisCategory'])){

    $restaurantId = $_SESSION['restaurant_id'];
    $categoryId = $_POST['categoryId'];

    print '<div class="modal fade" id="addItemForThisCategory_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" '.$dir.'>
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">'.getLanguage('aifcs').'</h5> 
                                    <div id="Notice__AddItemForThisCategory" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                               
                                 <form id="formAddItemForThisCategory">
                                       
                                    <input type="hidden" name="todo" id="todo" value="addItem">
                                    <input type="hidden" name="categoryId" id="categoryId" value="'.$categoryId.'">
                                    <input type="hidden" name="requestRand" id="requestRand" value="'.$_SESSION['thisRequest'].'">
                                                                                
                                    <dev class="">
                                                                                
                                         <div class="mb-3">
                                            <label for="itemName" class="mb-0"><h6>'.getLanguage('Item Name').'</h6></label>
                                            <input type="text" class="form-control" name="itemName" id="itemName" required>
                                         </div>
                                         
                                         <div class="mb-3">
                                            <label for="itemDetails" class="mb-0"><h6>'.getLanguage('Item Details').'</h6></label>
                                            <input type="text" class="form-control" name="itemDetails" id="itemDetails" >
                                         </div>
                                         
                                         <div class="mb-3">
                                            <label for="itemPrice" class="mb-0"><h6>'.getLanguage('Item Price').'</h6></label>
                                            <input type="text" class="form-control" name="itemPrice" id="itemPrice" required>
                                         </div> 
                                                                                                                                                                                                                             
                                         <div>
                                          <label for="itemImage" class="form-label mb-0"><h6>'.getLanguage('Select Image').'</h6></label>
                                          <input class="form-control form-control"  name="itemImage" id="itemImage" type="file">
                                        </div> 
                                         
                                    </dev>
                                                                                
                                    <div class="modal-footer">
                                        <button type="button"  class="btn btn-warning"  onclick="send_form(\'Notice__AddItemForThisCategory\',\'formAddItemForThisCategory\')">'.getLanguage("Add").'</button>
                                        <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                    </div>
                                    
                                </form>
                                
                                </div>
                            </div>
                        </div>
                    </div>';

    print '<script>$(\'#addItemForThisCategory_modal\').modal(\'show\');</script>';
}

if(isset($_POST['getModalEditCategory'])){

    $restaurantId = $_SESSION['restaurant_id'];
    $categoryId = trim($_POST['categoryId']);

    $query = "SELECT  `name` FROM `categorys` WHERE `id` = '$categoryId' AND `restaurant_id` = '$restaurantId'";
    $result = mysqli_query($GLOBALS['connectionSQL'], $query);
    $categoriesName =  mysqli_fetch_all($result, MYSQLI_ASSOC);

    $categoryName = '';

    foreach ($categoriesName as $cat) $categoryName = $cat['name'];


    print '<div class="modal fade" id="EditCategory_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" '.$dir.'>
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">'.getLanguage('Edit Category').'</h5>
                                     <div id="Notice__EditCategory" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                               
                                 <form id="formEditCategory">
                                       
                                    <input type="hidden" name="todo" id="todo" value="editCategory">
                                    <input type="hidden" name="categoryId" id="categoryId" value="'.$categoryId.'">
                                    <input type="hidden" name="requestRand" id="requestRand" value="'.$_SESSION['thisRequest'].'">
                                                                                                                       
                                         <div class="mb-3">
                                            <label for="categoryName"  class="mb-0"><h6>'.getLanguage('Name').'</h6></label>
                                            <input type="text" class="form-control" name="categoryName" id="categoryName" placeholder ="Category Name" value="'.$categoryName.'" required>
                                        </div>                                                                                              
                                                                                                                    
                                    <div class="modal-footer">
                                        <button type="button"  class="btn btn-warning"  onclick="send_form(\'Notice__EditCategory\',\'formEditCategory\')">'.getLanguage("Edit").'</button>
                                        <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                    </div>
                                    
                                </form>
                                
                                </div>
                            </div>
                        </div>
                    </div>';

    print '<script>$(\'#EditCategory_modal\').modal(\'show\');</script>';
}

if($_POST['todo'] == 'editCategory'){
    $categoryId = trim($_POST['categoryId']);
    $categoryName = trim($_POST['categoryName']);
    $restaurantId = $_SESSION['restaurant_id'];

    if(!isset($_SESSION['restaurant_id'])) { print '<script> notice(\'error\',\'Error not set RI\'); </script>'; die(); }
    if(empty(($categoryName)) || empty(($categoryId))) { print '<script> notice(\'warning\',\'This field is required\'); </script>'; die(); }

    $query = "UPDATE `categorys` SET `name`='$categoryName' WHERE `id` = '$categoryId' AND `restaurant_id` = '$restaurantId'";

    if(mysqli_query($GLOBALS['connectionSQL'], $query))
    {
        unset($_SESSION['thisRequest']);
        print '<script> notice(\'success\',\'Done\'); </script>';
        print '<script>$(\'#editCategory_modal\').modal(\'hide\');</script>';
        print '<script> location.reload(); </script>';
    }
}

if(isset($_POST['getModalDeleteCategory'])){

    $restaurantId = $_SESSION['restaurant_id'];
    $categoryId = trim($_POST['categoryId']);

    print '<div class="modal fade" id="deleteCategory_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" '.$dir.'>
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">'.getLanguage('Delete Category').'</h5>
                                    <div id="Notice__DeleteCategory" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                                
                                 <form id="formDeleteCategory">
                                       
                                    <input type="hidden" name="todo" id="todo" value="deleteCategory">
                                    <input type="hidden" name="categoryId" id="categoryId" value="'.$categoryId.'"> 
                                    <input type="hidden" name="requestRand" id="requestRand" value="'.$_SESSION['thisRequest'].'">
                                       
                                    <label for="categoryName"><h5>'.getLanguage('Note Accept Delete Cat').'</h5></label>                                                                                                                                                                                                    
                                                                                
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"  onclick="send_form(\'Notice__DeleteCategory\',\'formDeleteCategory\')">'.getLanguage("Delete").'</button>
                                        <button type="button" class="btn btn-warning"   data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                    </div>
                                    
                                </form>
                                
                                </div>
                            </div>
                        </div>
                    </div>';

    print '<script>$(\'#deleteCategory_modal\').modal(\'show\');</script>';
}

if($_POST['todo'] == 'deleteCategory'){
    $categoryId = trim($_POST['categoryId']);
    $restaurantId = $_SESSION['restaurant_id'];

    if(!isset($_SESSION['restaurant_id'])) { print '<script> notice(\'error\',\'Error not set RI\'); </script>'; die(); }
    if(empty(($categoryId))) { print '<script> notice(\'warning\',\'\'); </script>'; die(); }


    $query = "DELETE FROM `categorys` WHERE `id` = '$categoryId' AND `restaurant_id` = '$restaurantId'";

    try {
        if(mysqli_query($GLOBALS['connectionSQL'], $query))
        {
            unset($_SESSION['thisRequest']);
            print '<script> notice(\'success\',\'Done\'); </script>';
            print '<script>$(\'#deleteCategory_modal\').modal(\'hide\');</script>';
            print '<script> location.reload(); </script>';
        }
    }catch (exception $e) {
        print '<script> notice(\'error\',\'you cant delete this category\'); </script>';
        print '<script>$(\'#deleteCategory_modal\').modal(\'hide\');</script>';
    }
}

if(isset($_POST['getModalDeleteItem'])){

    $restaurantId = $_SESSION['restaurant_id'];
    $itemId = trim($_POST['itemId']);
    $categoryId = trim($_POST['catId']);


    print '<div class="modal fade" id="deleteItem_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" '.$dir.'>
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">'.getLanguage('Delete Item').'</h5>
                                    <div id="Notice__DeleteItem" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                                
                                 <form id="formDeleteItem">
                                       
                                    <input type="hidden" name="todo" id="todo" value="deleteItem">
                                    <input type="hidden" name="itemId" id="itemId" value="'.$itemId.'"> 
                                    <input type="hidden" name="catId" id="catId" value="'.$categoryId.'"> 
                                    <input type="hidden" name="requestRand" id="requestRand" value="'.$_SESSION['thisRequest'].'">
                                       
                                    <label for="categoryName"><h5>'.getLanguage('Note Accept Delete Item').'</h5></label>                                                                                                                                                                                                    
                                                                                
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"  onclick="send_form(\'Notice__DeleteItem\',\'formDeleteItem\')">'.getLanguage("Delete").'</button>
                                        <button type="button" class="btn btn-warning"   data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                    </div>
                                    
                                </form>
                                
                                </div>
                            </div>
                        </div>
                    </div>';

    print '<script>$(\'#deleteItem_modal\').modal(\'show\');</script>';
}

if($_POST['todo'] == 'deleteItem'){

    $itemId = trim($_POST['itemId']);
    $categoryId = trim($_POST['catId']);
    $restaurantId = $_SESSION['restaurant_id'];

    if(!isset($_SESSION['restaurant_id'])) { print '<script> notice(\'error\',\'Error not set RI\'); </script>'; die(); }
    if(empty(($itemId)) || empty($categoryId)) { print '<script> notice(\'warning\',\'\'); </script>'; die(); }

    $oldImgName = getOldImageItemName($itemId);

    $query = "DELETE FROM `items` WHERE `id` = '$itemId' AND `restaurant_id` = '$restaurantId'";

    try {
        if(mysqli_query($GLOBALS['connectionSQL'], $query))
        {
            deleteOldImageItem($oldImgName);
            unset($_SESSION['thisRequest']);
            print '<script> notice(\'success\',\'Done\'); </script>';
            print '<script>$(\'#deleteItem_modal\').modal(\'hide\');</script>';
            print '<script>deleteItemFromCategory("'.$categoryId.'","'.$itemId.'");</script>';
//            print '<script> location.reload(); </script>';
        }
    }catch (exception $e) {
        unset($_SESSION['thisRequest']);
        print '<script> notice(\'error\',\'you cant delete this item\'); </script>';
        print '<script>$(\'#deleteItem_modal\').modal(\'hide\');</script>';
    }
}

if(isset($_POST['getModalEditItem'])){

    $restaurantId = $_SESSION['restaurant_id'];
    $itemId = trim($_POST['itemId']);

    $query = "SELECT * FROM `items` WHERE `restaurant_id` = '$restaurantId' AND `id` = '$itemId'";
    $result = mysqli_query($GLOBALS['connectionSQL'], $query);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $itemName = $itemDetails = $itemPrice = '';

    foreach ($items as $item){
        $itemName = $item['name'];
        $itemDetails = $item['details'];
        $itemPrice = $item['price'];
        $itemPhoto = $item['photo'];
    }

    if(empty($itemPhoto)) $itemPhoto = 'rings.jpg';

    print '<div class="modal fade" id="editItem_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" '.$dir.'>
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">'.getLanguage('Edit Item').'</h5>
                                    <div id="Notice__EditItem" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                                
                                 <form id="formEditItem">
                                       
                                    <input type="hidden" name="todo" id="todo" value="EditItem">
                                    <input type="hidden" name="itemId" id="itemId" value="'.$itemId.'">
                                    <input type="hidden" name="requestRand" id="requestRand" value="'.$_SESSION['thisRequest'].'">
                                                                                
                                    <dev class="">
                                                                                
                                         <div class="mb-3">
                                            <label for="itemName" class="mb-0"><h6>'.getLanguage('Name').'</h6></label>
                                            <input type="text" class="form-control" name="itemName" id="itemName" placeholder ="item Name" value="'.$itemName.'" required>
                                         </div>
                                         
                                         <div class="mb-3">
                                           <label for="itemDetails" class="mb-0"><h6>'.getLanguage('Item Details').'</h6></label>
                                           <textarea class="form-control" name="itemDetails" id="itemDetails"  rows="4" placeholder ="item Details">'.$itemDetails.'</textarea>
                                         </div>
                                         
                                         <div class="mb-3">
                                            <label for="itemPrice" class="mb-0"><h6>'.getLanguage('Item Price').'</h6></label>
                                            <input type="number" class="form-control" name="itemPrice" id="itemPrice" value="'.$itemPrice.'" placeholder ="item Price" required>
                                         </div>
                                        
                                         <div class="row pb-2">
                                            <div class="col-6">                                            
                                                <label for="oldImage" class=""><h6>'.getLanguage('Old Image').'</h6></label>
                                                <img src="'._HOME_._DIR_FROM_ROOT_.'img/menu/'.$itemPhoto.'" id="oldImage" class="rounded" alt="" width="150" height="100">
                                            </div>
                                            <div class="col-6">
                                                <div>
                                                  <label for="itemImage" class=""><h6>'.getLanguage('Select Image').'</h6></label>
                                                  <input class="form-control form-control"  name="itemImage" id="itemImage" type="file">
                                                </div> 
                                            </div>
                                        </div>                                                                                                  
                                    
                                    </dev>
                                                                                
                                    <div class="modal-footer">
                                        <button type="button"  class="btn btn-warning"  onclick="send_form(\'Notice__EditItem\',\'formEditItem\')">'.getLanguage("Save").'</button>
                                        <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                    </div>
                                    
                                </form>
                                
                                </div>
                            </div>
                        </div>
                    </div>';

    print '<script>$(\'#editItem_modal\').modal(\'show\');</script>';
}

if($_POST['todo'] == 'EditItem'){
    $itemId = trim($_POST['itemId']);
    $itemName = trim($_POST['itemName']);
    $itemDetails = trim($_POST['itemDetails']);
    $itemPrice =  trim($_POST['itemPrice']);
    $image = $_FILES['itemImage'];

    $restaurantId = $_SESSION['restaurant_id'];

    if(!isset($_SESSION['restaurant_id'])) { print '<script> notice(\'error\',\'Error not set RI\'); </script>'; die(); }
    if(empty(($itemPrice)) || empty(($itemName)) ) { print '<script> notice(\'warning\',\'Name And Price Are Required\'); </script>'; die(); }

    $imageNewName = uploadImage($image,'menu');

    if(!empty($imageNewName)){
        deleteImageItem($itemId);
        $img = $imageNewName;
        $query = "UPDATE `items` SET `name`='$itemName',`details`='$itemDetails',`price`='$itemPrice', `photo`='$imageNewName' WHERE `id`='$itemId' AND `restaurant_id`='$restaurantId'";
    }else{
        $img = 'dontChange';
        $query = "UPDATE `items` SET `name`='$itemName',`details`='$itemDetails',`price`='$itemPrice' WHERE `id`='$itemId' AND `restaurant_id`='$restaurantId'";
    }

    try {
        if(mysqli_query($GLOBALS['connectionSQL'], $query))
        {
            unset($_SESSION['thisRequest']);
            print '<script> 
                       notice(\'success\',\'Done\');
                        $(\'#editItem_modal\').modal(\'hide\');
                         editItemInfo("item_'.$itemId.'","'.$itemName.'","'.$itemPrice.'","'.$itemDetails.'","'.$img.'");
                   </script>';
        }
    }catch (exception $e) {
        print '<script> notice(\'error\',\'Error\'); </script>';
    }

}

if(isset($_POST['getModalChangeMyMenuTheme'])) {

    $themesArray = getMenuThemes();

    foreach ($themesArray as $oneTheme){
        $options .= '<option value="'.$oneTheme.'">'.$oneTheme.'</option>';
    }

    $themesOptions = '<div class="container col-xl-12">
                            <div class="form-select form-select-sm mb-3 col-xl-12">
                                <label for="myMenuTheme">' . getLanguage('Theme') . '</label>
                                <select id="myMenuTheme" name="myMenuTheme" class="form-select">
                                '.$options.'
                                </select>
                            </div>
                        </div>';

    print  '<div class="modal fade" id="ChangeMyMenuTheme_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" '.$dir.'>
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">'.getLanguage("Change Menu Theme").'</h5>
                                    <div id="Notice__ChangeMyMenuTheme" class="text-center"></div>
                                </div>
                                <div class="modal-body">
                                     <form id="formChangeMyMenuTheme">
                                        <input type="hidden" name="todo" id="todo" value="changeMyMenuTheme">
                                        '.$themesOptions.'
                                        <div class="modal-footer text-center">
                                            <button type="button"  class="btn btn-warning"  onclick="send_form(\'Notice__ChangeMyMenuTheme\',\'formChangeMyMenuTheme\')" >'.getLanguage("Save").'</button>
                                            <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                        </div>
                                    </form>   
                                </div>
                            </div>
                        </div>
                    </div>';
    print '<script>$(\'#ChangeMyMenuTheme_modal\').modal(\'show\');</script>';
}

if($_POST['todo'] == 'changeMyMenuTheme'){
    $themeName = trim($_POST['myMenuTheme']);
    $themesArray = getMenuThemes();
    if(empty($themeName)) die();
    if(in_array($themeName,$themesArray,true)){
        $query = "UPDATE `restaurants` SET `theme`= '$themeName' WHERE `id`=".$_SESSION['restaurant_id'];
        if(mysqli_query($GLOBALS['connectionSQL'], $query)){
            print '<script> notice(\'success\',\'Done\'); </script>';
            unset($_SESSION['thisRequest']);
            print '<script>$(\'#ChangeMyMenuTheme_modal\').modal(\'hide\');</script>';
        }else {
            print '<script> notice(\'error\',\'error |code:257|\'); </script>';
        }
    }else {
        print '<script> notice(\'error\',\'error |code:258|\'); </script>';
    }


}

if(isset($_POST['getModalQR'])) {


    print '<div class="modal fade " id="QR_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">QR Code</h5>
                                </div>
                                <div class="modal-body">
                                  <div>
                                    <img src="'._HOME_._DIR_FROM_ROOT_.'img/qr/QR_'.$_SESSION['restaurantSP'].'.png'.'" class="container-fluid" style="max-width: 500px">
                                </div>
                                  <div CLASS="text-center">
                                  </div>
                                   
                                   
                                </div>
                                <div class="modal-footer"> 
                                    <a class="btn btn-warning no-print mt-1" href="'._HOME_._DIR_FROM_ROOT_.'img/qr/QR_'.$_SESSION['restaurantSP'].'.png'.'" download>'.getLanguage("Download").'</a>
                                    <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" >'.getLanguage("Close").'</button>
                                </div>
                            </div>
                        </div>
                    </div>';
    print '<script>$(\'#QR_modal\').modal(\'show\');</script>';

}
