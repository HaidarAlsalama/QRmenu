<?php
$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$photo = $_POST['pic'];
$date = date('Y-m-d h:i:s');

$query = "INSERT INTO `facebook_users`(`facebook_id`, `name`, `e-mail`, `photo`, `register_date`)
                                VALUES ('$id', '$name','$email','$photo','$date')";
mysqli_query($GLOBALS['connectionSQL'], $query);



?>