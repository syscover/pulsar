<?php

// Management of critical errrores
register_shutdown_function(function() {
    $lastError = error_get_last();

    if (!empty($lastError) && $lastError['type'] == E_ERROR)
    {
        $memoryLimit = ini_get('memory_limit');
        if(strpos($memoryLimit,'M'))
        {
            $memoryLimit = str_replace ('M', '', $memoryLimit);
            $memoryLimit  = (int)$memoryLimit * 1048576;
        }

        $response = [
            'success'       => false,
            'message'       => 'Status: 500 Internal Server Error',
            'result'        => [
                'memoryUsage'       => memory_get_usage(),
                'memoryLimit'       => $memoryLimit,
                'uploadMaxFilesize' => ini_get('upload_max_filesize'),
                'postMaxSize'       => ini_get('post_max_size'),
                'maxInputTime'      => ini_get('max_input_time'),
                'maxExecutionTime'  => ini_get('max_execution_time'),
                'fileUploads'       => ini_get('file_uploads'),
                'lastError'         => $lastError
            ]
        ];

        header('Content-type: application/json');
        http_response_code(500);
        echo json_encode($response);
    }
});

require_once('../Libraries/DbSQLite.php');

switch ($_POST['action'])
{
    case 'getCountries':
        $data = DbSqlite::getCountries($_POST['lang']);
        break;

    case 'getTerritorialArea1':
        $data = DbSqlite::getTerritorialAreas1($_POST['country']);
        break;

    case 'getTerritorialArea2':
        $data = DbSqlite::getTerritorialAreas2($_POST['territorialArea1']);
        break;

    case 'getTerritorialArea3':
        $data = DbSqlite::getTerritorialAreas3($_POST['territorialArea2']);
        break;
}

if(isset($data))
{
    header('Content-type: application/json');
    http_response_code(200);
    echo json_encode([
        'success'   => true,
        'data'      => $data
    ]);
}