<?php
if (file_exists('process/'.$_GET['all'][2 - _HOST_].'.php'))
{

    require_once 'process/'.$_GET['all'][2 - _HOST_].'.php';
}
else { print '<i class="text-danger">Error 808</i>'; die();}