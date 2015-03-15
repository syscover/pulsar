<?php

use Pulsar\Support\Facades\Input,
    Pulsar\Support\Facades\Config,
    Libraries\FileManager,
    Libraries\ImageManager;

class ImageServices
{
    private $documentRoot;
    
    public function __construct() {

        $this -> documentRoot = Config::get('documentRoot') == '' || Config::get('documentRoot') == null ? realpath((getenv('DOCUMENT_ROOT') && preg_match('/^' . preg_quote(realpath(getenv('DOCUMENT_ROOT')), '/') . '/', realpath(__FILE__))) ? getenv('DOCUMENT_ROOT') : str_replace(dirname(@$_SERVER['PHP_SELF']), '', str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__)))) : Config::get('documentRoot');
        $this -> documentRoot = str_replace('\\' , '/', $this -> documentRoot);
    }

    public function uploadImage() 
    {
        if(Input::get('folder') == 'null')																		// Folder variable validation
        {
            $response = array(
                'success'   => false,
                'error'     => 6,
                'message'   => 'Folder setting must be defined'
            );
        }
        elseif(!is_dir($this->documentRoot.Input::get('folder')))
        {
            $response = array(
                'success'   => false,
                'error'     => 5,
                'message'   => 'Destination directory does not exist. Check folder setting: '.$this->documentRoot.Input::get('folder')
            );
        }
        elseif(!is_writable($this->documentRoot.Input::get('folder'))){
            $response = array(
                'success'   => false,
                'error'     => 7,
                'message'   => 'Destination folder is not writeable. Please check permissions for: '.$this->documentRoot.Input::get('folder')
            );
        }
        elseif(Input::get('tmpFolder') == 'null')                                                                       // Validaton variable tmpFolder
        {
            $response = array(
                'success'   => false,
                'error'     => 9,
                'message'   => 'The tmpFolder setting must be defined'
            );
        }
        elseif(!is_dir($this->documentRoot.Input::get('tmpFolder')))
        {
            $response = array(
                'success'   => false,
                'error'     => 8,
                'message'   => 'Temp directory does not exist. Check tmpFolder setting: '.$this->documentRoot.Input::get('tmpFolder')
            );
        }
        elseif(!is_writable($this->documentRoot.Input::get('tmpFolder'))){
            $response = array(
                'success'   => false,
                'error'     => 10,
                'message'   => 'Temp folder is not writeable. Please check permissions for: '.$this->documentRoot.Input::get('tmpFolder')
            );
        }


        if(!isset($response))
        {
            $this->cleanTmpDirectory($this->documentRoot.Input::get('tmpFolder'), Input::get('tmpDelete'));		// Before uploading the file old temp files are deleted

            if(Input::hasFile('file'))
            {
                $file = Input::file('file');

                $response = array(
                    'success'       => true,
                    'action'        => 'upload',
                    'size'          => $file->getSize(),
                    'oldName'       => $file->getClientOriginalName(),
                    'extension'     => $file->getClientOriginalExtension(),
                    'mime'          => $file->getMimeType(),
                    'isImage'       => false,
                    //'tmpFile'       => $_FILES['file']['tmp_name'],
                    //'tmpFolder'     => Request::input('tmpFolder'),
                    //'folder'        => Request::input('folder'),
                    //'encryption'    => Request::input('encryption'),
                    //'filename'      => Request::input('filename'),
                    //'mimesAccept'   => Request::input('mimesAccept'),
                    //'tmpDelete'     => Request::input('tmpDelete'),

                );

                $mime = $file->getMimeType();																	// MIME Type check

                if(Input::get('mimesAccept') != 'false'){
                    $mimes = explode(',',Input::get('mimesAccept'));											// MIME Type array creation
                    if(FileManager::isMimeTypeAllowed($mime, $mimes))
                    {
                        $mimeAllowed = true;
                    }
                    else
                    {
                        $mimeAllowed = false;
                        $response = array(
                            'success'   => false,
                            'error'     => 2,
                            'message'   => 'MIME type: '.$mime.', is not allowed'
                        );
                    }
                }

                if($mime == 'image/gif' || $mime == 'image/png' || $mime == 'image/jpeg')						// Check whether it's a manipulable image file
                {
                    $response['isImage']    = true;

                    $this->checkOrientation($file->getRealPath(), $file->getClientOriginalExtension());         // Check orientation if upload file from iOs

                    list($width, $height)   = getimagesize($file->getRealPath());
                    $response['width']      = $width;
                    $response['height']     = $height;
                }

                if(Input::get('mimesAccept') == 'false' || $mimeAllowed)
                {
                    if($response['isImage'] == true && (Input::get('cropActive') == 'true' || Input::get('resizeActive') == 'true'|| Input::get('outputExtension') == 'gif' || Input::get('outputExtension') == 'jpg' || Input::get('outputExtension') == 'png'))         // If crop is enabled the file must be located in the temp folder in order to edit it
                    {
                        $response['name'] = FileManager::uploadFiles('file', $this->documentRoot.Input::get('tmpFolder'), Input::get('encryption') === 'true'? true: false, Input::get('filename') === 'false' || Input::get('filename') === 'null'? false : Input::get('filename'));
                    }
                    else
                    {
                        $response['name'] = FileManager::uploadFiles('file', $this->documentRoot.Input::get('folder'), Input::get('encryption') === 'true'? true: false, Input::get('filename') === 'false' || Input::get('filename') === 'null'? false : Input::get('filename'));
                    }
                }
            }
            else
            {
                $response = array(
                    'success'   => false,
                    'error'     => 1,
                    'message'   => 'Field file does not exist'
                );
            }
        }

        $data['json'] = json_encode($response);
        
        header('Content-type: application/json');
        echo $data['json'];
    }
    
    public function cropImage() 
    {
        
        $response = array(
                'success'           => true,
                'action'            => Input::get('action'),
                'size'              => Input::get('size'),
                'oldName'           => Input::get('oldName'),
                'extension'         => Input::get('extension'),
                'mime'              => Input::get('mime'),
                'isImage'           => Input::get('isImage'),
                'name'              => null,
                //'cropWidth'         => Request::input('cropWidth'),
                //'cropHeight'        => Request::input('cropHeight'),
                //'aspectRatio'       => Request::input('aspectRatio'),
                //'tmpName'           => Request::input('tmpName'),
                //'tmpFolder'         => Request::input('tmpFolder'),
                //'folder'            => Request::input('folder'),
                //'row'               => Request::input('row'),
                //'roh'               => Request::input('roh'),
                //'outputExtension'   => Request::input('outputExtension'),
                //'sizes'             => Request::input('sizes')
            );

        if(Input::has('coords')) $response['coords'] = Input::get('coords');									// Coordinates are included in the response if they exist

        //obtenemos las coordenadas, fuente y destino de la imagen
        $coords     = Input::get('coords');
        $srcPath    = $this->documentRoot.Input::get('tmpFolder').'/'.Input::get('tmpName');
        
        $dstPath    = $this->documentRoot.Input::get('folder').'/'.Input::get('tmpName');
        
        list($ancho, $alto, $tipo, $atributos) = getimagesize($srcPath);										// Real image parameters are obtained
        $srcX = 0;
        $srcY = 0;

        if (is_array($coords))																					// Making sure to get crop coordinates
        {
            // Crop parameters are obtained from the ratio between the resized image and the real image
            $srcW = ($coords['w'] * $ancho)     / Input::get('row');
            $srcH = ($coords['h'] * $alto)      / Input::get('roh');
            $srcX = ($coords['x'] * $ancho)     / Input::get('row');
            $srcY = ($coords['y'] * $alto)      / Input::get('roh');
        }
        else																									// If crop coordinates are not present, there is no crop feature and the image size ratio is not needed
        {
            $srcW = $ancho;
            $srcH = $alto;
        }
                
        $destW  = Input::get('cropWidth');
        $destH  = Input::get('cropHeight');
        
        if($destW == '' || $destH == '' || $destW == 'false' || $destH == 'false')								// In case of width or height values are not provided, cropping is performed using current real measures
        {
            $destW = $srcW;
            $destH = $srcH;
        }
        
        $response['name']   =  ImageManager::resizeImage($srcPath, $dstPath, Input::get('outputExtension'), $srcX, $srcY, $destW, $destH, $srcW, $srcH);
        
        //creamos otros tamaños
        $pathInfo           = pathinfo($dstPath);
        $responseSizes      = $this->setResizesImage(Input::get('sizes'), $pathInfo['dirname'].'/'.$response['name']);

        if(is_array($responseSizes)) $response['sizes'] = $responseSizes;

        $data['json']       = json_encode($response);
        
        header('Content-type: application/json');
        echo $data['json'];
    }

    // Function called when images need to be resized without cropping
    public function resizeImage() 
    {
        $response = array(
                'success'               => true,
                'action'                => Input::get('action'),
                'size'                  => Input::get('size'),
                'oldName'               => Input::get('oldName'),
                'extension'             => Input::get('extension'),
                'mime'                  => Input::get('mime'),
                'isImage'               => Input::get('isImage'),
                'name'                  => null,
                //'tmpName'               => Request::input('tmpName'),
                //'tmpFolder'             => Request::input('tmpFolder'),
                //'folder'                => Request::input('folder'),
                //'width'                 => Request::input('width'),
                //'height'                => Request::input('height'),
                //'constrainProportions'  => Request::input('constrainProportions'),
                //'outputExtension'       => Request::input('outputExtension'),
                //'sizes'                 => Request::input('sizes')
            );
             
        // Image source and target
        $srcPath    = $this->documentRoot.Input::get('tmpFolder').'/'.Input::get('tmpName');
        $dstPath    = $this->documentRoot.Input::get('folder').'/'.Input::get('tmpName');
        
        // Values for resize
        $values = $this->getResizeValues($srcPath, Input::get('constrainProportions'), Input::get('width'), Input::get('height'));
                
        $response['name'] = ImageManager::resizeImage($srcPath, $dstPath, Input::get('outputExtension'), $values['srcX'], $values['srcY'], $values['destW'], $values['destH'], $values['srcW'], $values['srcH']);
        
        // Other sizes
        $pathInfo   = pathinfo($dstPath);
        $responseSizes = $this->setResizesImage(Input::get('sizes'), $pathInfo['dirname'].'/'.$response['name']);

        if(is_array($responseSizes)) $response['sizes'] = $responseSizes;
                        
        $data['json'] = json_encode($response);
        
        header('Content-type: application/json');
        echo $data['json'];
    }

    // Function called from crop or resize actions, in case image copies are needed
    private function setResizesImage($sizes, $srcPath)
    {
        $pathInfo   = pathinfo($srcPath);      
        
        $response = array();

        if(!is_array($sizes)){
            return null;
        }

        foreach ($sizes as $size){
            
            if(!isset($size['folder']) || $size['folder'] == '' || $size['folder'] == 'false')
            {
                $size['folder'] = $pathInfo['dirname'];
            }
            else
            {
                $size['folder'] = $this->documentRoot.$size['folder'];
            }

            if(!isset($size['prefix']))
            {
                $size['prefix'] = '';
            }

            if(!isset($size['outputExtension']))
            {
                $size['outputExtension'] = null;
            }
            
            $dstPath = $size['folder'].'/'.$pathInfo['filename'].$size['prefix'].'.'.$pathInfo['extension'];
            
            $i = 0;
            while (file_exists($dstPath)) // If file already exists, a number is added
            {
                $i++;
                $dstPath = $size['folder'].'/'.$pathInfo['filename'].$size['prefix'].'-'.$i.'.'.$pathInfo['extension'];
            }

            $values = $this->getResizeValues($srcPath, isset($size['constrainProportions'])? $size['constrainProportions'] : true, $size['width'], $size['height']);
                                    
            $filename = ImageManager::resizeImage($srcPath, $dstPath, $size['outputExtension'], $values['srcX'], $values['srcY'], $values['destW'], $values['destH'], $values['srcW'], $values['srcH']);
            
            $size['name'] = $filename;

            $sizeResponse = array(
                //'width'                     => $size['width'],
                //'height'                    => $size['height'],
                //'constrainProportions'      => $size['constrainProportions'],
                //'prefix'                    => $size['prefix'],
                //'folder'                    => $size['folder'],
                'name'                      => $size['name']
            );
            
            array_push($response, $sizeResponse);
        }
        
        return $response;
    }
    
    private function getResizeValues($srcPath, $constrainProportions, $widht, $height)
    {
        
        $widht  = intval($widht);
        $height = intval($height);
        
        list($srcW, $srcH, $tipo, $atributos) = getimagesize($srcPath); 										// Real image parameters are obtained
                
        $srcX = 0;
        $srcY = 0;
        
        if($constrainProportions == 'true') // Size calculation (keeping proportions)
        {
            $destWTmp = ($srcW * $height) / $srcH;
            $destHTmp = ($srcH * $widht) / $srcW;

            if($destWTmp > $widht){
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
        
        if($destW == '' || $destH == '' || $destW == 'false' || $destH == 'false')									// If width and height values are not provided, crop is performed using the real measures
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

        if($extension == 'jpeg' || $extension == 'jpg')
        {
            $exif = exif_read_data($realPath);                                                                  // Only support to jpeg and tiff

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
                        //echo('6OK');
                        $image = imagerotate($image,-90,0);
                        break;
                }

                imagejpeg($image, $realPath);

            }
        }
    }
}