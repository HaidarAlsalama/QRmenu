<?php


class style
{
    static $head,$naveBar,$sidebar,$footer,$body;
    public function __construct(){

//        self::$pageTitle = strtoupper($_GET['job']) ;
        self::$head = $this->get_head();
        self::$footer = $this->get_footer();

        if($_GET['job'] != 'public' && $_GET['job'] != 'process' && $_GET['job'] != 'login'  && $_GET['job'] != 'register'){
            self::$naveBar = $this->get_NavBar();
            self::$sidebar = $this->get_SideBar();
            self::$body = $this->get_body();
        }
        else{
            self::$body = $this->get_bodyPublic();
            self::$naveBar = $this->get_NavBarPublic();
        }
    }

    private function get_head(){
        return str_replace(['{{DIR_FROM_ROOT}}'],[_HOME_._DIR_FROM_ROOT_],file_get_contents(_STYLE_.'head.rings'));
    }

    private function get_navBar(){
        return  file_get_contents(_STYLE_.'navBar.rings');
    }

    private function get_SideBar(){
        $search = [
            '{{LOGO_IMG}}','{{RINGS}}','{{APP_NAME}}','{{USER_IMG}}','{{USER_NAME}}',
            '{{URL_HOME}}','{{STRING_HOME}}','{{USER_LOGIN}}','{{STRING_USERS}}',
            '{{URL_USERS}}','{{STRING_SHOW_USERS}}','{{STRING_ADD_USERS}}',
            '{{STRING_RESTAURANTS}}','{{URL_RESTAURANTS}}','{{STRING_SHOW_RESTAURANTS}}',
            '{{STRING_ADD_RESTAURANTS}}','{{STRING_SETTINGS}}','{{URL_SETTINGS}}',
            '{{STRING_TESTAT}}','{{URL_TESTAT}}','{{STRING_LOGOUT}}'
        ];

        $replace = [
            _HOME_._DIR_FROM_ROOT_.'img/rings_white.png',
            'RINGS',_APPLICATION_NAME_,
            _HOME_._DIR_FROM_ROOT_.'img/user.png',
            getUserInfo($_SESSION['id'])['name'],
            _HOME_._DIR_FROM_ROOT_.'home',
            getLanguage('Home'),
            getUserInfo($_SESSION['id'])['login'],
            getLanguage('Users'),
            _HOME_._DIR_FROM_ROOT_.'users',
            getLanguage('ShowUsers'),
            getLanguage('Add User'),
            getLanguage('Restaurants'),
            _HOME_._DIR_FROM_ROOT_.'restaurants',
            getLanguage('ShowRestaurants'),
            getLanguage('Add Restaurant'),
            getLanguage('Settings'),
            _HOME_._DIR_FROM_ROOT_.'settings',
            getLanguage('Testat'),
            _HOME_._DIR_FROM_ROOT_.'testat',
            getLanguage('Logout')
        ];

        $temp = str_replace($search,$replace,file_get_contents(_STYLE_.'sideBar.rings'));

        if(!isset($_SESSION['id'])) {
            $str2f = "/<!-- ITEMS_SIDEBAR_START -->(.*?)<!-- ITEMS_SIDEBAR_END -->/s";
            return preg_replace($str2f, "", $temp);
        }

        if(getUserInfo($_SESSION['id'])['login'] == 'subordinate'
            || !isset(getUserPermissions($_SESSION['id'])['Add_New_Admin']) && getUserInfo($_SESSION['id'])['login'] == 'admin'
            && !isset(getUserPermissions($_SESSION['id'])['Add_New_User']) && getUserInfo($_SESSION['id'])['login'] == 'admin'
            || !isset(getUserPermissions($_SESSION['id'])['Add_New_subordinate']) && getUserInfo($_SESSION['id'])['login'] == 'user'
        ){

            if(getUserInfo($_SESSION['id'])['login'] == 'subordinate'
                || !isset(getUserPermissions($_SESSION['id'])['Show_All_Users']) && getUserInfo($_SESSION['id'])['login'] == 'admin'){


                $str2f = "/<!-- USERS_START -->(.*?)<!-- USERS_END -->/s";
                $temp = preg_replace($str2f, "", $temp);

            }else{

                $str2f = "/<!-- ADD_USER_START -->(.*?)<!-- ADD_USER_END -->/s";
                $temp = preg_replace($str2f, "", $temp);

            }
        }

        if(getUserInfo($_SESSION['id'])['login'] == 'subordinate'
            || !isset(getUserPermissions($_SESSION['id'])['Show_All_Users']) && getUserInfo($_SESSION['id'])['login'] == 'admin'){

            $str2f = "/<!-- SHOW_ALL_USERS_START -->(.*?)<!-- SHOW_ALL_USERS_END -->/s";
            $temp = preg_replace($str2f, "", $temp);

        }

        if(!getUserInfo($_SESSION['id'])['login'] != 'user' && !isset(getUserPermissions($_SESSION['id'])['Create_A_Restaurant'])){

            $str2f = "/<!-- ADD_RESTAURANT_START -->(.*?)<!-- ADD_RESTAURANT_END -->/s";
            $temp = preg_replace($str2f, "", $temp);
        }

        if(getUserInfo($_SESSION['id'])['login'] != 'user' && getUserInfo($_SESSION['id'])['login'] != 'subordinate'){

            $str2f = "/<!-- RESTAURANT_START -->(.*?)<!-- RESTAURANT_END -->/s";
            $temp = preg_replace($str2f, "", $temp);
        }

        return $temp;
    }

    private function get_footer(){
        return str_replace('{{VERSION}}' , _VERSION_,file_get_contents(_STYLE_.'footer.rings'));
    }

    static function get_body(){
        return file_get_contents(_STYLE_.'body.rings');
    }

    static function get_bodyPublic(){
        return file_get_contents(_STYLE_.'public/bodyPublic.rings');
    }

    static function get_navBarPublic(){
        return str_replace(['{{IMAGE}}','{{URL_TOS}}','{{URL_PP}}','{{URL_LOGIN}}','{{URL_REGISTER}}'],
                    [   _HOME_._DIR_FROM_ROOT_.'img/rings.png',
                        _HOME_._DIR_FROM_ROOT_.'public/terms_of_service',
                        _HOME_._DIR_FROM_ROOT_.'public/privacy_policy',
                        _HOME_._DIR_FROM_ROOT_.'login',
                        _HOME_._DIR_FROM_ROOT_.'register'],file_get_contents(_STYLE_.'public/navBarPublic.rings')

        );
    }
}