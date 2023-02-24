<?php
require 'process/functionsApp.inc';

class job
{
    static $Page,$script = null,$pageTitle;
    public function __construct()
    {
        if($_GET['job'] != 'login' && !isset($_SESSION['id'])
            && $_GET['job'] != 'menu' && $_GET['job'] != 'public'
                && $_GET['job'] != 'register')
                    $_GET['job'] = 'login' & header('LOCATION: '._HOME_._DIR_FROM_ROOT_.'login');

        if($_GET['job'] == 'login' && isset($_SESSION['id'])) $_GET['job'] = 'home' & header('LOCATION: '._HOME_._DIR_FROM_ROOT_.'home');

        self::$pageTitle = str_replace('_',' ',strtoupper($_GET['job'])) ;
        switch ($_GET['job']){
            case 'menu':{
                self::getMenu();
                break;
            }
            case 'public':{
                if($_GET['all'][2 - _HOST_] == 'privacy_policy') self::getPp();
                if($_GET['all'][2 - _HOST_] == 'terms_of_service') self::getTos();
                break;
            }
            case 'login':{
                self::getLogin();
                break;
            }
            case 'register':{
                self::getRegister();
                break;
            }
            default:{

                checkAccount();
                checkSession();
                checkVerification();
                updateLastSeen();

                if($_GET['job'] == 'users') self::getUsers();
                else if($_GET['job'] == 'home') self::getHome();
                else if($_GET['job'] == 'settings') self::getSettings();
                else if($_GET['job'] == 'restaurants') self::getRestaurants();
                else if($_GET['job'] == 'my_restaurant') self::getMyRestaurant();
                else if($_GET['job'] == 'my_menu') self::getMyMenu();
                break;
            }
        }
    }

    /** HOME Pages */
    private function getHome(){
        $cards = file_get_contents(_STYLE_.'home.rings');

        if(getUserInfo($_SESSION['id'])['login'] == 'subordinate' || !isset(getUserPermissions($_SESSION['id'])['Show_All_Users'])
            && getUserInfo($_SESSION['id'])['login'] == 'admin')
        {
            $str2f = "/<!-- CARD_SHOW_USERS_START -->(.*?)<!-- CARD_SHOW_USERS_END -->/s";
            $cards = preg_replace($str2f, "", $cards);
        }

        if(getUserInfo($_SESSION['id'])['login'] != 'user' && getUserInfo($_SESSION['id'])['login'] != 'subordinate'){
            $str2f = "/<!-- CARD_SHOW_RESTAURANTS_START -->(.*?)<!-- CARD_SHOW_RESTAURANTS_END -->/s";
            $cards = preg_replace($str2f, "", $cards);
        }

        $cards = str_replace(['{{STRING_USERS}}','{{STRING_RESTAURANTS}}','{{STRING_SETTINGS}}'],[getLanguage('Users'),getLanguage('Restaurants'),getLanguage('Settings')],$cards);


        self::$Page = $cards;
        self::$script = scriptActive('home');
    }

    /** USERS Pages */
    private function getUsers(){
        if(getUserInfo($_SESSION['id'])['login'] == 'subordinate' || !isset(getUserPermissions($_SESSION['id'])['Show_All_Users'])
            && getUserInfo($_SESSION['id'])['login'] == 'admin') die('Not Allowed 😴');

        $temp = '<div class="overflow-auto">
                    <div class="container-fluid col-xl-10">
                <!-- main page her -->

                    <table class="table  text-center" id="userTable" style="font-size: 13px;">
                        <thead class="text-center">
                        <tr>
                            <th scope="col">'.getLanguage('ID').'</th>
                            <th scope="col">'.getLanguage('login').'</th>
                            <th scope="col">'.getLanguage('Full Name').'</th>
                            <th scope="col">'.getLanguage('UserName').'</th>
                            <th scope="col">'.getLanguage('Parent Name').'</th>
                            <th scope="col">'.getLanguage('Status').'</th>
                            <th scope="col" style="width: 30px">'.getLanguage('More info').'</th>
                            <th scope="col">'.getLanguage('Tools').'</th>

                        </tr>
                        </thead>
                        <tbody>';

        if(getUserInfo($_SESSION['id'])['login'] == 'user')
        {
            $query = "SELECT `id`, `login`, `name`, `username`, `e-mail`, `mobile`, `language`,`address`, `register_date`, `status` FROM `users` WHERE `parent_id` = ".$_SESSION['id'];
            $result = mysqli_query($GLOBALS['connectionSQL'], $query);
            $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }else
        {
            $users = getUserInfo('all');
        }


        foreach ($users as $user){

            if($user['login'] == 'SUPER admin') continue;

            if($user['status'] == 0) $Status = '<i class="fas fa-user text-danger"></i>';
            else $Status = '<i class="fas fas fa-user text-success"></i>';

            if($user['id'] != $_SESSION['id'] ) $editUser = '<button type="button"  class="btn btn-outline-success btn-sm" onclick="send_data(\'spinner_'.$user['id'].'\',\'getModalForUserTool&userId_EF='.$user['id'].'\') " > '.getLanguage('Edit').' </button>
                                                                            <div id="spinner_'.$user['id'].'"></div>';
            else $editUser = ' ';

            $temp .= ' <tr id="row_id_'.$user['id'].'">
                                  <th >'.$user['id'].'</th>
                                  <td>'.$user['login'].'</td>
                                  <td>'.$user['name'].'</td>
                                  <td>'.base64_decode($user['username']).'</td>
                                  <td>'.getParentName($user['parent_id']).'</td>
                                  <td>'.$Status.'</td>
                                  <td>
                                      <button type="button"  class="btn btn-outline-success btn-sm" onclick="send_data(\'spinner_MI_'.$user['id'].'\',\'getModalForMoreInfo&userId_MI='.$user['id'].'\') " ><i class="fas fa-info"></i></button>                  
                                    <div id="spinner_MI_'.$user['id'].'"></div>
                                </td>
                                  <td>'.$editUser.'</td>
                                </tr>';
        }

        self::$Page = $temp.'</tbody></table></div></div></div>';
        self::$script = scriptActive('users',1);
    }

    /** SETTINGS Pages */
    private function getSettings(){

        $cards = file_get_contents(_STYLE_.'settings.rings');
        $cards = str_replace(['{{STRING_EDIT_LANGUAGES}}','{{STRING_EDIT_PASSWORD}}'],[getLanguage('Edit language'),getLanguage('Edit Password')],$cards);
        self::$Page = $cards;
        self::$script = scriptActive('settings');
    }

    /** LOGIN Pages */
    private function getLogin(){
        $str2f = "/<!-- LOGIN_START -->(.*?)<!-- LOGIN_END -->/s";
        preg_match($str2f, file_get_contents(_STYLE_.'public/login.rings'),$login);
        $str2f = "/<!-- SCRIPT_START -->(.*?)<!-- SCRIPT_END -->/s";
        preg_match($str2f, file_get_contents(_STYLE_.'public/login.rings'),$script);

        self::$Page = str_replace('{{IMAGE}}',_HOME_._DIR_FROM_ROOT_.'img/rings.png',$login[0]);
        self::$script = $script[0];
    }

    /** REGISTER Pages */
    private function getRegister(){
        $str2f = "/<!-- REGISTER_START -->(.*?)<!-- REGISTER_END -->/s";
        preg_match($str2f, file_get_contents(_STYLE_.'public/register.rings'),$login);
        $str2f = "/<!-- SCRIPT_START -->(.*?)<!-- SCRIPT_END -->/s";
        preg_match($str2f, file_get_contents(_STYLE_.'public/register.rings'),$script);

        self::$Page = str_replace('{{IMAGE}}',_HOME_._DIR_FROM_ROOT_.'img/rings.png',$login[0]);
        self::$script = $script[0];
    }

    /** privacy_policy Pages */
    private function getPp(){
        self::$Page = file_get_contents(_STYLE_.'public/privacyPolicy.rings');
        self::$script = scriptActive('privacy_policy');
    }

    /** terms_of_service Pages */
    private function getTos(){
        self::$Page = file_get_contents(_STYLE_.'public/termsOfService.rings');
        self::$script = scriptActive('terms_of_service');
    }

    static function out(){
        /** for restaurants */
        if(isset($_SESSION['restaurant_id'])) $label = getRestaurantInfo($_SESSION['restaurantSP'])['name'];

        if(empty(self::$Page)) {
            self::$Page = str_replace('{{URL}}',_HOME_._DIR_FROM_ROOT_.'home',file_get_contents(_STYLE_.'public/404.rings'));
            self::$pageTitle = 'ERROR 404';$label = null;
        }
        $html = null;
        $html .= style::$head.style::$body;
        $html = str_replace(['{{PAGE_TITLE}}','{{NAV_BAR}}','{{SIDE_BAR}}','{{CONTENT}}','{{LABEL}}','{{SCRIPT}}','{{FOOTER}}'] ,
            [self::$pageTitle,style::$naveBar,style::$sidebar,self::$Page,$label,self::$script,style::$footer],$html);

        print $html;
    }




    /** RESTAURANTS Pages */
    private function getRestaurants(){

        if(getUserInfo($_SESSION['id'])['login'] != 'subordinate' && getUserInfo($_SESSION['id'])['login'] != 'user')
        {
            return "<script>location.href ='"._HOME_._DIR_FROM_ROOT_."';</script>";
        }

        if(getUserInfo($_SESSION['id'])['login'] == 'user') $user_id = $_SESSION['id'];
        if(getUserInfo($_SESSION['id'])['login'] == 'subordinate') $user_id = getUserInfo($_SESSION['id'])['parent_id'];

        $query = "SELECT * FROM `restaurants` WHERE `user_id` = ".$user_id;
        $result = mysqli_query($GLOBALS['connectionSQL'], $query);
        $restaurants = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $temp = '<div class=" row">';
        foreach ($restaurants as $restaurant)
        {
            if(sizeof($restaurants) < 2)
            {
                print "<script>location.href ='"._HOME_._DIR_FROM_ROOT_."my_restaurant/".$restaurant['nameSpecial']."';</script>";
                die();
            }

            $temp .= str_replace(
                ['{{RESTAURANT_SP}}','{{IMG}}','{{RESTAURANT_NAME}}','{{RESTAURANT_LOCATION}}','{{THEME}}','{{RESTAURANT_THEME}}','{{VISITORS}}','{{RESTAURANT_VISITORS}}'],
                [$restaurant['nameSpecial'],_DIR_FROM_ROOT_.'img/rings.png',$restaurant['name'],$restaurant['location'],getLanguage('Theme'),$restaurant['theme'],getLanguage('Visitors'),$restaurant['visitors']],
                file_get_contents(_STYLE_.'restaurants.rings')
            );

        }

        self::$Page = $temp.'</div>';
        self::$script = scriptActive('restaurants',1);
    }
    /** MY RESTAURANT Pages */
    private function getMyRestaurant(){
        $restaurantSP = $_SESSION['restaurantSP'] = trim($_GET['all'][2 - _HOST_]);
        $restaurantInfo = getRestaurantInfo($restaurantSP);
        $restaurant_id = $_SESSION['restaurant_id'] = $restaurantInfo['id'];
        $userInfo = getUserInfo($_SESSION['id']);
        if(!empty($userInfo['parent_id'])) $userInfo = getUserInfo($userInfo['parent_id']);

        /** FOR control any restaurant add a validate hear **/
        if($restaurantInfo['owner_id'] != $userInfo['id']) {
            header('LOCATION: '._HOME_._DIR_FROM_ROOT_.'restaurants');
            die();
        }

        $URL  = _HOME_._DIR_FROM_ROOT_.'menu/'.$_SESSION['restaurantSP'];

        $fname = 'QR_'.$_SESSION['restaurantSP'].'.png';
        $file = __DIR__.'/../img/qr/'.$fname;

        if(!file_exists($file)) {
            QRcode::png($URL,$file,QR_ECLEVEL_H,60,2);
        }


        $cards = file_get_contents(_STYLE_.'myRestaurant.rings');
        $cards = str_replace(['{{URL_RESTAURANT_SP}}','{{URL_RESTAURANT_PREVIEW}}','{{Menu}}','{{Preview}}','{{QR Code}}','{{Theme}}'],
            [_HOME_._DIR_FROM_ROOT_.'my_menu/'.$restaurantSP,_HOME_._DIR_FROM_ROOT_.'menu/'.$restaurantSP,getLanguage('Menu'),getLanguage('Preview'),getLanguage('QR Code'),getLanguage('Theme')],$cards);
        self::$Page = $cards;
        self::$script = scriptActive('restaurants',1);
    }
    /** MY MENU Pages */
    private function getMyMenu(){
        $restaurantSP = $_SESSION['restaurantSP'] = trim($_GET['all'][2 - _HOST_]);
        $restaurantInfo = getRestaurantInfo($restaurantSP);
        $restaurant_id = $_SESSION['restaurant_id'] = $restaurantInfo['id'];

        $userInfo = getUserInfo($_SESSION['id']);
        if(!empty($userInfo['parent_id'])) $userInfo = getUserInfo($userInfo['parent_id']);

        /** FOR control any restaurant add a validate hear **/
        if($restaurantInfo['owner_id'] != $userInfo['id']) {
            header('LOCATION: '._HOME_._DIR_FROM_ROOT_.'restaurants');
            die();
        }

        $html = file_get_contents(_STYLE_.'myMenu.rings');

        $str2f = "/<!-- TOP_START -->(.*?)<!-- TOP_END -->/s";
        preg_match($str2f,$html,$top);

        $str2f = "/<!-- CARD_START -->(.*?)<!-- CARD_END -->/s";
        preg_match($str2f,$html,$card);

        $str2f = "/<!-- ITEM_RTL_START -->(.*?)<!-- ITEM_RTL_END -->/s";
        preg_match($str2f,$html,$item_rtl);

        $outPut = '<div class="container-fluid">'.$top[0];

        $query = "SELECT * FROM `categorys` WHERE `restaurant_id` = ".$restaurant_id;
        $result = mysqli_query($GLOBALS['connectionSQL'], $query);
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

        foreach ($categories as $category){

            $query = "SELECT * FROM `items` WHERE `restaurant_id` = '$restaurant_id' AND `category_id` = ".$category['id'];
            $result = mysqli_query($GLOBALS['connectionSQL'], $query);
            $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

            $contentTemp = '';

            foreach ($items as $item){

                if(empty($item['photo']))
                    $photo = _HOME_._DIR_FROM_ROOT_.'img/menu/rings.jpg';
                else
                    $photo = _HOME_._DIR_FROM_ROOT_.'img/menu/'.$item['photo'];

                $contentTemp .= str_replace(['{{CATEGORY_ID}}','{{ITEM_ID}}','{{NAME}}','{{IMAGE}}','{{DETAILS}}','{{PRICE}}'],
                    [$category['id'],$item['id'],$item['name'],$photo,$item['details'],$item['price'],],$item_rtl[0]);

            }

            $outPut .= str_replace(['{{CONTENT}}','{{CATEGORY_ID}}','{{CATEGORY_NAME}}'],
                [$contentTemp,$category['id'],$category['name']],$card[0]);

        }

        self::$Page = $outPut.'</div>';
        self::$script = scriptActive('restaurants',1);
    }

    private function getMenu(){/**/
        $restaurantSP=$_GET['all'][2 - _HOST_];
        $info = getRestaurantInfo($restaurantSP);
        if($info['theme'] == 'D-Photo')self::TFM_menuWithPhoto($info);
        else if($info['theme'] == 'D-NoPhoto')self::TFM_menuWithoutPhoto($info);
        else if($info['theme'] == 'Shuffle')self::TFM_shuffle($info);

    }
    private function TFM_menuWithPhoto($info){

        $html = file_get_contents(_STYLE_.'menu/theme/withPhoto.rings');

        $str2f = "/<!-- CARD_START -->(.*?)<!-- CARD_END -->/s";
        preg_match($str2f,$html,$card);

        $str2f = "/<!-- ITEM_RTL_START -->(.*?)<!-- ITEM_RTL_END -->/s";
        preg_match($str2f,$html,$item_rtl);

        $str2f = "/<!-- FOR_DELETE_START -->(.*?)<!-- FOR_DELETE_END -->/s";
        $body = preg_replace($str2f,'',$html);

        $content = '<div class="container-fluid col-xl-10" id="menu">';

        $query = "SELECT * FROM `categorys` WHERE `restaurant_id` = ".$info['id'];
        $result = mysqli_query($GLOBALS['connectionSQL'], $query);
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

        foreach ($categories as $category){

            $query = "SELECT * FROM `items` WHERE `restaurant_id` = ".$info['id']." AND `category_id` = ".$category['id'];
            $result = mysqli_query($GLOBALS['connectionSQL'], $query);
            $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

            $contentTemp = '';

            foreach ($items as $item){

                if(empty($item['photo']))
                    $photo = _HOME_._DIR_FROM_ROOT_.'img/menu/rings.jpg';
                else
                    $photo = _HOME_._DIR_FROM_ROOT_.'img/menu/'.$item['photo'];

                $contentTemp .= str_replace(['{{ITEM_ID}}','{{NAME}}','{{IMAGE}}','{{DETAILS}}','{{PRICE}}'],
                    [$item['id'],$item['name'],$photo,$item['details'],$item['price'],],$item_rtl[0]);

            }

            $content .= str_replace(['{{CONTENT}}','{{CATEGORY_ID}}','{{CATEGORY_NAME}}'],
                [$contentTemp,$category['id'],$category['name']],$card[0]);

        }
        $restaurantName = str_replace('_',' ',$info['name']);
        $str2f = "/<!-- NAVE_BAR_START -->(.*?)<!-- NAVE_BAR_END -->/s";
        preg_match($str2f,$html,$navBar);
        $navBar[0] = str_replace('{{NAME}}',$restaurantName,$navBar[0]);

        self::$pageTitle = $info['name'].' menu';
        style::$naveBar = $navBar[0];
        $str2f = "/<!-- My Js start -->(.*?)<!-- My Js end -->/s";
        style::$head = preg_replace($str2f,'',str_replace(['{{DIR_FROM_ROOT}}'],[_HOME_._DIR_FROM_ROOT_],file_get_contents(_STYLE_.'head.rings')));
        style::$body = $body;
        self::$Page = $content.'</div>';
    }
    private function TFM_menuWithoutPhoto($info){
            $html = file_get_contents(_STYLE_.'menu/theme/withoutPhoto.rings');

            $str2f = "/<!-- CARD_START -->(.*?)<!-- CARD_END -->/s";
            preg_match($str2f,$html,$card);

            $str2f = "/<!-- ITEM_RTL_START -->(.*?)<!-- ITEM_RTL_END -->/s";
            preg_match($str2f,$html,$item_rtl);

            $str2f = "/<!-- FOR_DELETE_START -->(.*?)<!-- FOR_DELETE_END -->/s";
            $body = preg_replace($str2f,'',$html);

            $content = '<div class="container-fluid col-xl-10" id="menu">';

            $query = "SELECT * FROM `categorys` WHERE `restaurant_id` = ".$info['id'];
            $result = mysqli_query($GLOBALS['connectionSQL'], $query);
            $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

            foreach ($categories as $category){

                $query = "SELECT * FROM `items` WHERE `restaurant_id` = ".$info['id']." AND `category_id` = ".$category['id'];
                $result = mysqli_query($GLOBALS['connectionSQL'], $query);
                $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

                $contentTemp = '';

                foreach ($items as $item){

                    if(empty($item['photo']))
                        $photo = _HOME_._DIR_FROM_ROOT_.'img/menu/rings.jpg';
                    else
                        $photo = _HOME_._DIR_FROM_ROOT_.'img/menu/'.$item['photo'];

                    $contentTemp .= str_replace(['{{ITEM_ID}}','{{NAME}}','{{IMAGE}}','{{DETAILS}}','{{PRICE}}'],
                        [$item['id'],$item['name'],$photo,$item['details'],$item['price'],],$item_rtl[0]);

                }

                $content .= str_replace(['{{CONTENT}}','{{CATEGORY_ID}}','{{CATEGORY_NAME}}'],
                    [$contentTemp,$category['id'],$category['name']],$card[0]);

            }
            $restaurantName = str_replace('_',' ',$info['name']);
            $str2f = "/<!-- NAVE_BAR_START -->(.*?)<!-- NAVE_BAR_END -->/s";
            preg_match($str2f,$html,$navBar);
            $navBar[0] = str_replace('{{NAME}}',$restaurantName,$navBar[0]);


        self::$pageTitle = $info['name'].' menu';
        style::$naveBar = $navBar[0];
        $str2f = "/<!-- My Js start -->(.*?)<!-- My Js end -->/s";
        style::$head = preg_replace($str2f,'',str_replace(['{{DIR_FROM_ROOT}}'],[_HOME_._DIR_FROM_ROOT_],file_get_contents(_STYLE_.'head.rings')));
        style::$body = $body;
        self::$Page = $content.'</div>';
    }
    private function TFM_shuffle($info){

        $html = file_get_contents(_STYLE_.'menu/theme/shuffle.rings');

        $str2f = "/<!-- ITEM_RTL_START -->(.*?)<!-- ITEM_RTL_END -->/s";
        preg_match($str2f,$html,$item_rtl);

        $str2f = "/<!-- CAROUSE_START -->(.*?)<!-- CAROUSE_END -->/s";
        preg_match($str2f,$html,$carouse);

        $str2f = "/<!-- FOR_DELETE_START -->(.*?)<!-- FOR_DELETE_END -->/s";
        $body = preg_replace($str2f,'',$html);

        $content = '<div class="container-fluid menu" id="menu"><div class="row">';
$cc = '';
        $query = "SELECT * FROM `categorys` WHERE `restaurant_id` = ".$info['id'];
        $result = mysqli_query($GLOBALS['connectionSQL'], $query);
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $lable = '<div class="container-fluid text-center shuffle-category"  id="startMenu" dir="rtl">
                    <span class="pr-1 pl-1 m-1 btn btn-sm btn-outline-secondary filter active" data-filter="all">الكل</span>';

        foreach ($categories as $category){
            $lable .= '<span class="pr-1 pl-1 m-1 btn btn-sm btn-outline-secondary filter" data-filter=".cat-'.$category['id'].'">'.$category['name'].'</span>';
            $query = "SELECT * FROM `items` WHERE `restaurant_id` = ".$info['id']." AND `category_id` = ".$category['id'];
            $result = mysqli_query($GLOBALS['connectionSQL'], $query);
            $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

            $contentTemp = $carouseTemp = '';

            foreach ($items as $item){

                if(empty($item['photo']))
                    $photo = _HOME_._DIR_FROM_ROOT_.'img/menu/rings.jpg';
                else{
                    $photo = _HOME_._DIR_FROM_ROOT_.'img/menu/'.$item['photo'];
                    $carouseTemp .= str_replace('{{URL_IMG}}',$photo,$carouse[0]);
                }

                $contentTemp .= str_replace(['{{ID}}','{{ITEM_ID}}','{{NAME}}','{{IMAGE}}','{{DETAILS}}','{{PRICE}}'],
                    [$category['id'],$item['id'],$item['name'],$photo,$item['details'],$item['price'],],$item_rtl[0]);

            }
            $cc .= $carouseTemp;
            $content .= $contentTemp;
        }

        $content .= '</div>';
        $temp = $lable.'</div>'.$content;
        $restaurantName = str_replace('_',' ',$info['name']);
        $str2f = "/<!-- NAVE_BAR_START -->(.*?)<!-- NAVE_BAR_END -->/s";
        preg_match($str2f,$html,$navBar);
        $navBar[0] = str_replace('{{NAME}}',$restaurantName,$navBar[0]);

        self::$pageTitle = $info['name'].' menu';
        style::$naveBar = $navBar[0];

        style::$head = style::$footer = null;

        style::$body = str_replace(['{{DIR_FROM_ROOT}}','{{CAROUSE}}'],[_HOME_._DIR_FROM_ROOT_,$cc],$body);
        self::$Page = $temp.'</div>';
    }

}
