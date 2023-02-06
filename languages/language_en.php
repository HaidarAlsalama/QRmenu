<?php
function getLanguage($string){

        static $lang_en = array(
            'UserName'=>'@UserName',

            'Login'=>'Login',
            'Show Users'=>'Show Users',


            'Management'=>'Management',


            'ID'=>'ID',
            'login'=>'Login',
            'Tools'=>'Tools',
            'Last Seen'=>'Last Seen',
            'Parent Name'=>'Parent Name',
            'Status'=>'Status',
            'More info'=>'More info',
            'Register Date'=>'Register Date',
            'Active'=>'Active',
            'Disable'=>'Disable',


            //Language ****
            'Edit language'=>'Edit Language',



            //Password ****
            'Password'=>'Password',
            'Old Password'=>'Old Password',
            'New Password'=>'New Password',
            'Confirm Password'=>'Confirm Password',
            'Edit Password'=>'Edit Password',

            //Edit ****
            'Edit Information'=>'Edit Information',
            'Edit Permission'=>'Edit Permission',
            'Edit User:'=>'Edit User: ',
            'Edit'=>'Edit',


            'Close'=>'Close','Save'=>'Save',

            'Add User'=>'Add User',
            'Add'=>'Add',
            'Add Restaurant'=>'Add Restaurant',

            'Logout'=>'Log-Out',

            'Information User'=>'Information User',
            'Information Connection'=>'Information Connection',
            'More Information For:'=>'More Information For:',


            'Mobile'=>'Mobile Number',
            'Full Name'=>'Full Name',
            'E-mail'=>'E-mail',
            'Address'=>'Address',
            'Language'=>'Language',
            'Account Status'=>'Account Status',
            'How Added'=>'Add by',
            'Delete'=>'Delete',

            'Name'=>'Name',

            'Control Status User'=>'Control Status User',

            'IP'=>'IP','Browser'=>'Browser','OS'=>'OS','Device'=>'Device',

            //Pages ****
            'Home'=>'Home',
            'Testat'=>'Testat',
            'Settings'=>'Settings',
            'Users'=>'Users',
            'Restaurants'=>'Restaurants',

            'ShowUsers'=>'Show Users',
            'ShowRestaurants'=>'Show Restaurants',

            //Note ****
            'Note Accept Delete Item'=>'Are you sure to delete this item??',
            'Note Accept Delete Cat'=>'Are you sure to delete this category??',

                 // modal edit item
            'Edit Item'=>'Edit Item',
            'Item Name'=>'Name',
            'Item Details'=>'Details',
            'Item Price'=>'Price',
            'Select Image'=>'Select a New Image',
            'Old Image'=>'Old Image',

                // modal delete item
            'Delete Item'=>'Delete Item',

                // modal delete category
            'Delete Category'=>'Delete Category',

                // modal edit category
            'Edit Category'=>'Edit Category',

                // modal Add Item For Category Selected
            'aifcs'=>'Add Item For Category Selected',

                // modal Add Item
            'Select Category'=>'Select Category',
            'Add Item'=>'Add Item',
            'Search'=>'Search...',

                // modal Add Category
            'Add Category'=>'Add Category',

            'Theme'=>'Theme',
            'Visitors'=>'Visitors',

                'note for accept'=>'Are you sure',
                'Change Menu Theme'=>'Change Menu Theme',

                'Download'=>'Download',
                'Menu'=>'Menu',
                'Preview'=>'Preview',
                'QR Code'=>'QR Code',


        );

        return $lang_en[$string];
}

?>
