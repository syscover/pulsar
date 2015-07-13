<?php
// Management of critical errrores
register_shutdown_function(function() {
    $lastError = error_get_last();

    if (!empty($lastError) && $lastError['type'] == E_ERROR)
    {
        header('Status: 500 Internal Server Error');
        header('HTTP/1.0 500 Internal Server Error');

        $memoryLimit = ini_get('memory_limit');
        if(strpos($memoryLimit,'M'))
        {
            $memoryLimit = str_replace ('M', '', $memoryLimit);
            $memoryLimit  = (int)$memoryLimit * 1048576;
        }

        $response = [
            'success'       => false,
            'error'         => 100,
            'message'       => 'Status: 500 Internal Server Error',
            'lastError'     => $lastError,
            'serverStatus'  => [
                'memoryUsage'       => memory_get_usage(),
                'memoryLimit'       => $memoryLimit,
                'uploadMaxFilesize' => ini_get('upload_max_filesize'),
                'postMaxSize'       => ini_get('post_max_size'),
                'maxInputTime'      => ini_get('max_input_time'),
                'maxExecutionTime'  => ini_get('max_execution_time'),
                'fileUploads'       => ini_get('file_uploads')
            ]
        ];

        $data['json'] = json_encode($response);

        header('Content-type: application/json');
        echo $data['json'];
    }
});

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
    case 'copies':
        $imageServices->copiesImage();
        break;
    case 'delete':
        $imageServices->delete();
        break;
    case 'getvars':
        $imageServices->getVars();
        break;
    case 'getinfosrc':
        $imageServices->getInfoSrc();
        break;
}