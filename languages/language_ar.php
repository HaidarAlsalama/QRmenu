<?php
function getLanguage($string){

        static $lang_ar = array(
            'UserName'=>'اسم المستخدم@',

            'Login'=>'تسجيل الدخول',
            'Show Users'=>'عرض المستخدمين',


            'Management'=>'ادارة',


            'ID'=>'الرمز',
            'login'=>'الصفة',
            'Tools'=>'الادوات',
            'Last Seen'=>'اخر ظهور',
            'Status'=>'الحالة',
            'Parent Name'=>'تابع لـ',
            'More info'=>'المزيد من المعلومات',
            'Register Date'=>'تاريخ التسجيل',
            'Active'=>'مفعل',
            'Disable'=>'معطل',


                //Language ****
            'Edit language'=>'تعديل اللغة',



                //Password ****
            'Password'=>'كلمة المرور',
            'Old Password'=>'كلمة المرور القديمة',
            'New Password'=>'كلمة المرور الجديدة',
            'Edit Password'=>'تعديل كلمة المرور',
            'Confirm Password'=>'تأكيد كلمة المرور',

                //Edit ****
            'Edit Information'=>'تعديل المعلومات',
            'Edit Permission'=>'تعديل الصلاحيات',
            'Edit User:'=>'Edit User: ',
            'Edit'=>' تعديل',



            'Close'=>'إغلاق','Save'=>'حفظ',

            'Add User'=>'إضافة مستخدم',
            'Add'=>'إضافة',
            'Add Restaurant'=>'اضافة مطعم',
            'Logout'=>'تسجيل الخروج',

            'Information User'=>'معلومات المستخدم',
            'Information Connection'=>'معلومات الاتصال',
            'More Information For:'=>'المزيد من المعلومات لـ: ',
            'Name'=>'الاسم',

            'Mobile'=>'رقم الموبايل',
            'Full Name'=>'الاسم الكامل',
            'E-mail'=>'البريد الإلكتروني',
            'Address'=>'العنوان',
            'Language'=>'اللغة',
            'Account Status'=>'حالة الحساب',
            'How Added'=>'اضافه',
            'Delete'=>'حذف',

            'Control Status User'=>'التحكم بحالة الحساب',

            'IP'=>'عنوان الاتصال','Browser'=>'المتصفح','OS'=>'زظام التشغيل','Device'=>'الجهاز',

                //Pages ****
            'Home'=>'الرئيسية',
            'Testat'=>'مختبر',
            'Settings'=>'الإعدادات',
            'Users'=>'المستخدمين',
            'Restaurants'=>'المطاعم',

            'ShowUsers'=>'عرض المستخدمين',
            'ShowRestaurants'=>'عرض المطاعم',

                //Note ****
            'Note Accept Delete Item'=>'هل انت متأكد من حذف هذه المادة؟؟',
            'Note Accept Delete Cat'=>'هل انت متأكد من حذف هذا الصنف؟؟',

                // modal edit item
            'Edit Item'=>'تعديل مادة',
            'Item Name'=>'الاسم',
            'Item Details'=>'التفاصيل',
            'Item Price'=>'السعر',
            'Select Image'=>'حدد صورة جديدة',
            'Old Image'=>'الصورة القديمة',

                // modal delete item
            'Delete Item'=>'حذف مادة',

                // modal delete category
            'Delete Category'=>'حذف صنف',

                // modal edit category
            'Edit Category'=>'تعديل صنف',

                // modal Add Item For Category Selected
            'aifcs'=>'اضافة مادة الى الصنف المحدد',

                // modal Add Item
            'Select Category'=>'حدد الصنف',
            'Add Item'=>'اضافة عنصر',
            'Search'=>'ابحث هنا ...',

                // modal Add Category
            'Add Category'=>'اضافة صنف جديد',

            'Theme'=>'الثيم',
            'Visitors'=>'الزيارات',

            'note for accept'=>'هل انت متأكد.',
            'Change Menu Theme'=>'تعديل الثيم',

                'Download'=>'تحميل',
            'Menu'=>'القائمة',
            'Preview'=>'معاينة',
            'QR Code'=>'QR Code',
        );

        return $lang_ar[$string];
}
?>
