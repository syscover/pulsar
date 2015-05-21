<?php

require_once('../Libraries/LaravelBridge/start.php');
require_once('../Libraries/FileManager.php');
require_once('../Libraries/ImageManager.php');
require_once('ImageServices.php');

$imageServices = new ImageServices();

switch ($_POST['action']) {
    case 'upload':
            $imageServices->uploadImage();
    break;
    case 'crop':
            $imageServices->cropImage();
    break;
    case 'resize':
            $imageServices->resizeImage();
    break;
    case 'change':
            $imageServices->cropImage();
    break;
}