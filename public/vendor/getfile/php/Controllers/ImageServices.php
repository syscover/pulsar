<?php

use Pulsar\Support\Facades\Input,
    Pulsar\Support\Facades\Config,
    Libraries\FileManager,
    Libraries\ImageManager;

class ImageServices
{
    private $documentRoot;
    
    public function __construct() {

        $this->documentRoot = Config::get('documentRoot') == '' || Config::get('documentRoot') == null ? realpath((getenv('DOCUMENT_ROOT') && preg_match('/^' . preg_quote(realpath(getenv('DOCUMENT_ROOT')), '/') . '/', realpath(__FILE__))) ? getenv('DOCUMENT_ROOT') : str_replace(dirname(@$_SERVER['PHP_SELF']), '', str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__)))) : Config::get('documentRoot');
        $this->documentRoot = str_replace('\\' , '/', $this -> documentRoot);
    }

    public function uploadImage()
    {
        if(Input::get('folder') == 'null')																		// Folder variable validation
        {
            $response = [
                'success'   => false,
                'error'     => 6,
                'message'   => 'Folder setting must be defined'
            ];
        }
        elseif(!is_dir($this->documentRoot.Input::get('folder')))
        {
            $response = [
                'success'   => false,
                'error'     => 5,
                'message'   => 'Destination directory does not exist. Check folder setting: '.$this->documentRoot.Input::get('folder')
            ];
        }
        elseif(!is_writable($this->documentRoot.Input::get('folder'))){
            $response = [
                'success'   => false,
                'error'     => 7,
                'message'   => 'Destination folder is not writeable. Please check permissions for: '.$this->documentRoot.Input::get('folder')
            ];
        }
        elseif(Input::get('tmpFolder') == 'null')                                                                       // Validaton variable tmpFolder
        {
            $response = [
                'success'   => false,
                'error'     => 9,
                'message'   => 'The tmpFolder setting must be defined'
            ];
        }
        elseif(!is_dir($this->documentRoot.Input::get('tmpFolder')))
        {
            $response = [
                'success'   => false,
                'error'     => 8,
                'message'   => 'Temp directory does not exist. Check tmpFolder setting: '.$this->documentRoot.Input::get('tmpFolder')
            ];
        }
        elseif(!is_writable($this->documentRoot.Input::get('tmpFolder'))){
            $response = [
                'success'   => false,
                'error'     => 10,
                'message'   => 'Temp folder is not writeable. Please check permissions for: '.$this->documentRoot.Input::get('tmpFolder')
            ];
        }

        if(!isset($response))
        {
            if(Input::get('activateTmpDelete') == "true")
            {
                $this->cleanTmpDirectory($this->documentRoot . Input::get('tmpFolder'), Input::get('tmpDelete'));		// Before uploading the file old temp files are deleted
            }

            if(Input::hasFile('file') || (Input::hasFile('file0') && Input::get('multiple') == "true"))
            {
                if(Input::get('multiple') == "true" && Input::hasFile('file0'))
                {
                    $nFiles = (int)Input::get('nFiles');
                    $responseUploads = [];

                    for($i=0; $i < $nFiles; $i++)
                    {
                        $responseAux        = $this->processUpload('file'.$i);
                        $responseUploads[]  = $responseAux;
                    }
                    $response = [
                        'success'   => true,
                        'multiple'  => (boolean)Input::get('multiple'),
                        'files'     => $responseUploads
                    ];
                }
                else
                {
                    $response = $this->processUpload();
                }
            }
            else
            {
                $response = [
                    'success'   => false,
                    'error'     => 1,
                    'message'   => 'Field file does not exist'
                ];
            }
        }

        $data['json'] = json_encode($response);
        
        header('Content-type: application/json');
        echo $data['json'];
    }

    private function processUpload($fileName = 'file')
    {
        $file = Input::file($fileName);

        if(!$file->existTempFile()){
            return [
                'success'   => false,
                'error'     => 14,
                'message'   => 'Temp file doesn\'t exist, may be the file uploaded file is too large'
            ];
        }

        $mime = $file->getMimeType();																	// MIME Type check

        $response = [
            'success'       => true,
            'action'        => 'upload',
            'folder'        => Input::get('folder'),
            'size'          => $file->getSize(),
            'oldName'       => $file->getClientOriginalName(),
            'extension'     => $file->getClientOriginalExtension(),
            'mime'          => $mime,
            'isImage'       => false
        ];

        if(Input::get('mimesAccept') != 'false')
        {
            $mimes = explode(',',Input::get('mimesAccept'));											// MIME Type array creation
            if(FileManager::isMimeTypeAllowed($mime, $mimes))
            {
                $mimeAllowed = true;
            }
            else
            {
                $mimeAllowed = false;
                $response = [
                    'success'   => false,
                    'error'     => 2,
                    'message'   => 'MIME type: ' . $mime . ', is not allowed'
                ];
            }
        }

        // Check whether it's a manipulable image file
        if($mime == 'image/gif' || $mime == 'image/png' || $mime == 'image/jpeg')
        {
            $response['isImage']    = true;

            if(function_exists('exif_read_data'))
            {
                // Check orientation if upload file from iOs
                $this->checkOrientation($file->getRealPath(), $file->getClientOriginalExtension());
            }
            else
            {
                return [
                    'success'   => false,
                    'error'     => 19,
                    'message'   => 'Error, please, enabled EXIF extension in your PHP version, to check image orientation'
                ];
            }

            list($width, $height)   = getimagesize($file->getRealPath());
            $response['width']      = $width;
            $response['height']     = $height;
        }

        if(Input::get('mimesAccept') == 'false' || $mimeAllowed)
        {
            if($response['isImage'] == true && (Input::get('cropActive') == 'true' || Input::get('resizeActive') == 'true'|| Input::get('outputExtension') == 'gif' || Input::get('outputExtension') == 'jpg' || Input::get('outputExtension') == 'png'))         // If crop is enabled the file must be located in the temp folder in order to edit it
            {
                $response['name'] = FileManager::uploadFiles($fileName, $this->documentRoot.Input::get('tmpFolder'), Input::get('encryption') === 'true'? true: false, Input::get('filename') === 'false' || Input::get('filename') === 'null'? false : Input::get('filename'));
            }
            else
            {
                $response['name'] = FileManager::uploadFiles($fileName, $this->documentRoot.Input::get('folder'), Input::get('encryption') === 'true'? true: false, Input::get('filename') === 'false' || Input::get('filename') === 'null'? false : Input::get('filename'));
            }
        }

        return $response;
    }

    public function cropImage() 
    {
        if(Input::get('multiple') == "true")
        {
            $files         = Input::get('files');
            $filesResponse = [];

            foreach($files as $file)
            {
                if($file['isImage'] == 'true')
                {
                    $args = [
                        'action'                => Input::get('action'),
                        'size'                  => $file['size'],
                        'oldName'               => $file['oldName'],
                        'extension'             => $file['extension'],
                        'mime'                  => $file['mime'],
                        'isImage'               => $file['isImage'],
                        'tmpFolder'             => Input::get('tmpFolder'),
                        'tmpName'               => $file['name'],           //tmpName is the name
                        'folder'                => Input::get('folder'),
                        'outputExtension'       => Input::get('outputExtension'),
                        'quality'               => Input::get('quality'),
                        'copies'                => Input::get('copies')
                    ];

                    $responseAux = $this->processCropImage($args);
                    array_push($filesResponse, $responseAux);
                }
                else
                {
                    array_push($filesResponse, $file);
                }
            }

            $response = [
                'success'       => true,
                'multiple'      => true,
                'files'         => $filesResponse
            ];
        }
        else
        {
            $args = [
                'action'                => Input::get('action'),
                'size'                  => Input::get('size'),
                'oldName'               => Input::get('oldName'),
                'extension'             => Input::get('extension'),
                'mime'                  => Input::get('mime'),
                'isImage'               => Input::get('isImage'),
                'tmpFolder'             => Input::get('tmpFolder'),
                'tmpName'               => Input::get('tmpName'),
                'folder'                => Input::get('folder'),
                'outputExtension'       => Input::get('outputExtension'),
                'quality'               => Input::get('quality'),
                'copies'                => Input::get('copies')
            ];

            $response = $this->processCropImage($args);
        }

        $data['json']       = json_encode($response);
        
        header('Content-type: application/json');
        echo $data['json'];
    }

    private function processCropImage($args)
    {
        $response = [
            'success'           => true,
            'action'            => $args['action'],
            'folder'            => Input::get('folder'),
            'size'              => $args['size'],
            'oldName'           => $args['oldName'],
            'extension'         => $args['extension'],
            'mime'              => $args['mime'],
            'isImage'           => $args['isImage'],
            'name'              => null
        ];

        if(Input::has('coords')) $response['coords'] = Input::get('coords');									// Coordinates are included in the response if they exist

        $coords     = Input::get('coords');

        $srcPath    = $this->documentRoot . $args['tmpFolder'] . '/' . $args['tmpName'];
        $dstPath    = $this->documentRoot . $args['folder'] . '/' . $args['tmpName'];

        // Real image parameters are obtained
        list($width, $height, $type, $attributes) = getimagesize($srcPath);
        $srcX = 0;
        $srcY = 0;

        // Making sure to get crop coordinates
        if (is_array($coords))
        {
            // Crop parameters are obtained from the ratio between the resized image and the real image
            $srcW = $coords['width'];
            $srcH = $coords['height'];
            $srcX = $coords['x'];
            $srcY = $coords['y'];
        }
        // If crop coordinates are not present, there is no crop feature and the image size ratio is not needed
        else
        {
            $srcW = $width;
            $srcH = $height;
        }

        $destW  = Input::get('cropWidth');
        $destH  = Input::get('cropHeight');

        // In case of width or height values are not provided, cropping is performed using current real measures
        if($destW == '' || $destH == '' || $destW == 'false' || $destH == 'false')
        {
            $destW = $srcW;
            $destH = $srcH;
        }

        $response['name']   =  ImageManager::resizeImage($srcPath, $dstPath, $args['outputExtension'], $srcX, $srcY, $destW, $destH, $srcW, $srcH, $args['quality']);

        // we create other sizes
        $pathInfo           = pathinfo($dstPath);

        $responseCopies     = $this->processCopiesImage(['copies' => $args['copies'], 'srcPath' => $pathInfo['dirname'] . '/' . $response['name']]);

        if(is_array($responseCopies)) $response['copies'] = $responseCopies;

        return $response;
    }

    // Function called when images need to be resized without cropping
    public function resizeImage() 
    {
        if(Input::get('multiple') == "true")
        {
            $files         = Input::get('files');
            $filesResponse = [];

            foreach($files as $file)
            {
                if($file['isImage'] == 'true')
                {
                    $args = [
                        'action'                => Input::get('action'),
                        'size'                  => $file['size'],
                        'oldName'               => $file['oldName'],
                        'extension'             => $file['extension'],
                        'mime'                  => $file['mime'],
                        'isImage'               => $file['isImage'],
                        'tmpFolder'             => Input::get('tmpFolder'),
                        'tmpName'               => $file['name'],           //tmpName is the name
                        'folder'                => Input::get('folder'),
                        'constrainProportions'  => Input::get('constrainProportions'),
                        'quality'               => Input::get('quality'),
                        'width'                 => Input::get('width'),
                        'height'                => Input::get('height'),
                        'outputExtension'       => Input::get('outputExtension'),
                        'copies'                => Input::get('copies')
                    ];

                    $responseAux = $this->processResizeImage($args);
                    array_push($filesResponse, $responseAux);
                }
                else
                {
                    array_push($filesResponse, $file);
                }
            }

            $response = [
                'success'       => true,
                'multiple'      => true,
                'files'         => $filesResponse
            ];
        }
        else
        {
            $args = [
                'action'                => Input::get('action'),
                'size'                  => Input::get('size'),
                'oldName'               => Input::get('oldName'),
                'extension'             => Input::get('extension'),
                'mime'                  => Input::get('mime'),
                'isImage'               => Input::get('isImage'),
                'tmpFolder'             => Input::get('tmpFolder'),
                'tmpName'               => Input::get('tmpName'),
                'folder'                => Input::get('folder'),
                'constrainProportions'  => Input::get('constrainProportions'),
                'quality'               => Input::get('quality'),
                'width'                 => Input::get('width'),
                'height'                => Input::get('height'),
                'outputExtension'       => Input::get('outputExtension'),
                'copies'                => Input::get('copies')
            ];

            $response = $this->processResizeImage($args);
        }

        $data['json'] = json_encode($response);
        
        header('Content-type: application/json');
        echo $data['json'];
    }

    private function processResizeImage($args)
    {
        $response = [
            'success'               => true,
            'action'                => $args['action'],
            'folder'                => Input::get('folder'),
            'size'                  => $args['size'],
            'oldName'               => $args['oldName'],
            'extension'             => $args['extension'],
            'mime'                  => $args['mime'],
            'isImage'               => $args['isImage'],
            'name'                  => null
        ];

        // Image source and target
        $srcPath    = $this->documentRoot . $args['tmpFolder'] . '/' . $args['tmpName'];
        $dstPath    = $this->documentRoot . $args['folder'] . '/' . $args['tmpName'];

        // Values for resize
        $values = $this->getResizeValues($srcPath, $args['constrainProportions'], $args['width'], $args['height']);

        $response['name'] = ImageManager::resizeImage($srcPath, $dstPath, $args['outputExtension'], $values['srcX'], $values['srcY'], $values['destW'], $values['destH'], $values['srcW'], $values['srcH'], $args['quality']);

        // Other copies
        $pathInfo       = pathinfo($dstPath);
        $responseCopies  = $this->processCopiesImage(['copies' => $args['copies'], 'srcPath' => $pathInfo['dirname'] . '/' . $response['name']]);

        if(is_array($responseCopies)) $response['copies'] = $responseCopies;

        return $response;
    }

    public function copiesImage()
    {
        if(Input::get('multiple') == "true")
        {
            $files         = Input::get('files');
            $filesResponse = [];

            foreach($files as $file)
            {
                if($file['isImage'] == 'true')
                {
                    $response = [
                        "success"               => true,
                        'action'                => Input::get('action'),
                        'size'                  => $file['size'],
                        'oldName'               => $file['oldName'],
                        'extension'             => $file['extension'],
                        'mime'                  => $file['mime'],
                        'isImage'               => $file['isImage'],
                        'name'                  => $file['name'],
                        'copies'                => null
                    ];

                    $args = [
                        'folder'                => Input::get('folder'),
                        'name'                  => $file['name'],
                        'copies'                => Input::get('copies'),
                        'width'                 => $file['width'],
                        'height'                => $file['height']
                    ];

                    $response['copies'] = $this->processCopiesImage([
                            'copies'    => $args['copies'],
                            'srcPath'   => $this->documentRoot . $args['folder'] . '/' . $args['name'],
                            'width'     => $args['width'],
                            'height'    => $args['height']
                        ]);

                    array_push($filesResponse, $response);
                }
                else
                {
                    array_push($filesResponse, $file);
                }
            }

            $response = [
                'success'       => true,
                'multiple'      => true,
                'files'         => $filesResponse
            ];
        }
        else
        {
            $response = [
                'success'           => true,
                'action'            => Input::get('action'),
                'size'              => Input::get('size'),
                'oldName'           => Input::get('oldName'),
                'extension'         => Input::get('extension'),
                'mime'              => Input::get('mime'),
                'isImage'           => Input::get('isImage'),
                'name'              => Input::get('name'),
                'copies'            => null
            ];

            $args = [
                'folder'                => Input::get('folder'),
                'name'                  => Input::get('name'),
                'copies'                => Input::get('copies'),
                'width'                 => Input::get('width'),
                'height'                => Input::get('height'),
            ];

            $response['copies'] = $this->processCopiesImage([
                    'copies'    => $args['copies'],
                    'srcPath'   => $this->documentRoot . $args['folder'] . '/' . $args['name'],
                    'width'     => $args['width'],
                    'height'    => $args['height']
                ]);
        }

        $data['json'] = json_encode($response);

        header('Content-type: application/json');
        echo $data['json'];
    }

    // Function called from crop or resize actions, in case image copies are needed
    private function processCopiesImage($args)
    {
       // var_dump($args); exit();
        $pathInfo   = pathinfo($args['srcPath']);
        
        $response = array();

        if(!is_array($args['copies'])){
            return null;
        }

        foreach ($args['copies'] as $copy){
            
            if(!isset($copy['folder']) || $copy['folder'] == '' || $copy['folder'] == 'false')
            {
                $folderRelative = $args['srcPath'];
                $copy['folder'] = $pathInfo['dirname'];
            }
            else
            {
                $folderRelative = $copy['folder'];
                $copy['folder'] = $this->documentRoot . $copy['folder'];
            }

            if(!isset($copy['prefix']))
            {
                $copy['prefix'] = '';
            }

            if(!isset($copy['outputExtension']))
            {
                $copy['outputExtension'] = null;
            }
            
            $dstPath = $copy['folder'] . '/' . $pathInfo['filename'] . $copy['prefix'] . '.' . $pathInfo['extension'];
            
            $i = 0;
            while (file_exists($dstPath)) // If file already exists, a number is added
            {
                $i++;
                $dstPath = $copy['folder'].'/'.$pathInfo['filename'].$copy['prefix'].'-'.$i.'.'.$pathInfo['extension'];
            }

            if(!isset($copy['width'])) $copy['width'] = $args['width'];
            if(!isset($copy['height'])) $copy['height'] = $args['height'];

            $values = $this->getResizeValues($args['srcPath'], isset($copy['constrainProportions'])? $copy['constrainProportions'] : true, $copy['width'], $copy['height']);

            try
            {
                $copy['name'] = ImageManager::resizeImage($args['srcPath'], $dstPath, $copy['outputExtension'], $values['srcX'], $values['srcY'], $values['destW'], $values['destH'], $values['srcW'], $values['srcH'], $copy['quality']);

                $copyResponse = [
                    'success'                   => true,
                    'folder'                    => $folderRelative,
                    'name'                      => $copy['name']
                ];
            }
            catch (\Exception $e)
            {
                $copyResponse = [
                    'success'                   => false,
                    'error'                     => 18,
                    'message'                   => $e->getMessage()

                ];
            }
            
            array_push($response, $copyResponse);
        }
        
        return $response;
    }

    public function delete()
    {
        $filenames = Input::get('filenames');

        if(is_array($filenames))
        {
            $response = [];

            foreach($filenames as $filename)
            {
                if (file_exists($this->documentRoot.$filename))
                {
                    unlink($this->documentRoot.$filename);

                    $responseAux = [
                        'success'   => true,
                        'action'    => 'delete',
                        'filename'  => $filename
                    ];
                }
                else
                {
                    $responseAux = [
                        'success'   => false,
                        'error'     => 17,
                        'message'   => 'Error to delete file, file: ' . $filenames . ', doesn\'t exist'
                    ];
                }
                array_push($response, $responseAux);
            }
        }
        else {

            if (file_exists($this->documentRoot.$filenames))
            {
                unlink($this->documentRoot.$filenames);

                $response = [
                    'success'   => true,
                    'action'    => 'delete',
                    'filename'  => $filenames
                ];
            }
            else
            {
                $response = [
                    'success'   => false,
                    'error'     => 17,
                    'message'   => 'Error to delete file, file: ' . $filenames . ', doesn\'t exist'
                ];
            }
        }

        $data['json'] = json_encode($response);

        header('Content-type: application/json');
        echo $data['json'];
    }

    public function getVars()
    {
        $response = [
            'uploadMaxFilesize' => ini_get('upload_max_filesize'),
            'postMaxSize'       => ini_get('post_max_size'),
            'maxInputTime'      => ini_get('max_input_time'),
            'maxExecutionTime'  => ini_get('max_execution_time'),
            'memoryLimit'       => ini_get('memory_limit'),
            'fileUploads'       => ini_get('file_uploads')
        ];

        $data['json'] = json_encode($response);

        header('Content-type: application/json');
        echo $data['json'];
    }

    public function getInfoSrc()
    {
        $src = $this->documentRoot .Input::get('src');
        list($width, $height)   = getimagesize($src);

        $response = [
            'success'       => true,
            'action'        => 'getinfosrc',
            'folder'        => pathinfo($src, PATHINFO_DIRNAME),
            'size'          => filesize($src),
            'oldName'       => pathinfo($src, PATHINFO_BASENAME),
            'extension'     => pathinfo($src, PATHINFO_EXTENSION),
            'width'         => $width,
            'height'        => $height,
            'name'          => pathinfo($src, PATHINFO_FILENAME)
        ];

        $data['json'] = json_encode($response);

        header('Content-type: application/json');
        echo $data['json'];
    }

    private function getResizeValues($srcPath, $constrainProportions, $widht, $height)
    {
        $widht  = intval($widht);
        $height = intval($height);

        // Real image parameters are obtained
        list($srcW, $srcH, $type, $attributes) = getimagesize($srcPath);
                
        $srcX = 0;
        $srcY = 0;

        // Size calculation (keeping proportions)
        if($constrainProportions == 'true')
        {
            $destWTmp = ($srcW * $height) / $srcH;
            $destHTmp = ($srcH * $widht) / $srcW;

            if($destWTmp > $widht)
            {
                $destW = $widht; 
                $destH = $destHTmp;
            }
            else
            {
                $destW = $destWTmp;
                $destH = $height;
            }

        }
        else
        {
            $destW  = $widht;
            $destH  = $height;
        }

        // If width and height values are not provided, crop is performed using the real measures
        if($destW == '' || $destH == '' || $destW == 'false' || $destH == 'false')
        {
            $destW = $srcW;
            $destH = $srcH;
        }
        
        $values = array(
            'srcW'      => $srcW,
            'srcH'      => $srcH,
            'srcX'      => $srcX,
            'srcY'      => $srcY,
            'destW'     => $destW,
            'destH'     => $destH,
        );
        
        return $values;
    }

    private function cleanTmpDirectory($tmpDirectory, $tmpDelete){

        if ($gestor = opendir($tmpDirectory))
        {
            while (false !== ($file = readdir($gestor)))
            {
                if(substr($file, 0, 1) != '.')
                {
                    $time = filemtime ($tmpDirectory . '/' . $file);
                    $now = date('U');

                    if($now-$time > $tmpDelete){
                        unlink($tmpDirectory . '/' . $file);
                    }
                }
            }
        }
    }

    private function checkOrientation($realPath, $extension)
    {
        $extension = strtolower($extension);

        if(($extension == 'jpeg' || $extension == 'jpg') && function_exists('exif_read_data'))
        {
            // Only support to jpeg and tiff
            $exif = exif_read_data($realPath);

            if(!empty($exif['Orientation']))
            {
                $image = imagecreatefromstring(file_get_contents($realPath));
                switch($exif['Orientation'])
                {
                    case 8:
                        $image = imagerotate($image,90,0);
                        break;
                    case 3:
                        $image = imagerotate($image,180,0);
                        break;
                    case 6:
                        $image = imagerotate($image,-90,0);
                        break;
                }
                imagejpeg($image, $realPath);
            }
        }
    }
}