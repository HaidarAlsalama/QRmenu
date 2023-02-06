<?php
function uploadImage($image,$path){
    $imageName = $image['name'];
    $imageType = $image['type'];
    $imageTemp = $image['tmp_name'];
    $imageSize = $image['size'];
    $imageError = $image['error'];
    $imagePath = $_SERVER['DOCUMENT_ROOT'] . _DIR_FROM_ROOT_ . 'img/'.$path.'/';

//    dump($image);
//    print 'Image Name: ' . $imageName . '<br>';
//    print 'Image Type: ' . $imageType . '<br>';
//    print 'Image Temp: ' . $imageTemp . '<br>';
//    print 'Image Path: ' . $imagePath . '<br>';
//    print 'Image Size: ' . $imageSize . '<br>';
    $imageNewName = null;

    if($imageError != 4):
        $allowedExtensions = array('jpg','jpeg','png');
        $array = explode('.',$imageName);
        $imageExtension = strtolower(end($array));

        if(! in_array($imageExtension,$allowedExtensions)):
            print '<div class="alert alert-danger text-center m-2"  role="alert">Please select Image</div>';
            die();
        endif;

        if($imageSize > 20000000):
            print '<div class="alert alert-danger text-center m-2"  role="alert">The Size Image So Big</div>';
            die();
        endif;

        if(isset($_SESSION['restaurantSP']))
             $imageNewName = $_SESSION['restaurantSP'].'_'.$_SESSION['id'].'_'.$_SESSION['restaurant_id'].'_'.time().'-'.rand(0,1000000000).'.'. $imageExtension;
        else $imageNewName = $_SESSION['id'].'_'.time().'-'.rand(0,1000000000).'.'. $imageExtension;
        move_uploaded_file($imageTemp,$imagePath.$imageNewName);

        $size = getimagesize($imagePath.$imageNewName);
//        dump($size);
        $width = $size[0];
        $height = $size[1];

        $resize = 0.5;
        $rwidth = (850);//720
        $rheight = (480);//360

        if($imageExtension == 'png')
            $original = imagecreatefrompng($imagePath.$imageNewName);
        else
            $original = imagecreatefromjpeg($imagePath.$imageNewName);

        $resized = imagecreatetruecolor($rwidth,$rheight);
        imagecopyresampled(
            $resized, $original,
            0, 0, 0, 0,
            $rwidth, $rheight,
            $width, $height
        );

        imagejpeg($resized, $imagePath.$imageNewName);

    endif;
    return $imageNewName;
}