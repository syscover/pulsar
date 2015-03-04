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

        $response = array(
            'success'       => false,
            'error'         => 100,
            'message'       => 'Status: 500 Internal Server Error',
            'lastError'     => $lastError,
            'serverStatus'  => array(
                'memoryUsage'       => memory_get_usage(),
                'memoryLimit'       => $memoryLimit,
                'uploadMaxFilesize' => ini_get('upload_max_filesize'),
                'postMaxSize'       => ini_get('post_max_size'),
                'maxInputTime'      => ini_get('max_input_time'),
                'maxExecutionTime'  => ini_get('max_execution_time'),
                'fileUploads'       => ini_get('file_uploads')
            )
        );

        $data['json'] = json_encode($response);

        header('Content-type: application/json');
        echo $data['json'];
    }
});

require_once('../Libraries/DbSQLite.php');

switch ($_POST['action'])
{
    case 'getCountries':
        $reponse = DbSqlite::getCountries($_POST['lang']);
        echo json_encode($reponse);
        break;

    case 'getTerritorialArea1':
        $reponse = DbSqlite::getTerritorialAreas1($_POST['country']);
        echo json_encode($reponse);
        break;

    case 'getTerritorialArea2':
        $reponse = DbSqlite::getTerritorialAreas2($_POST['territorialArea1']);
        echo json_encode($reponse);
        break;

    case 'getTerritorialArea3':
        $reponse = DbSqlite::getTerritorialAreas3($_POST['territorialArea2']);
        echo json_encode($reponse);
        break;
}