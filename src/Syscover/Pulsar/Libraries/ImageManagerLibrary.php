<?php namespace Syscover\Pulsar\Libraries;

class ImageManagerLibrary
{
    /**
     *    Resize image
     *
     * @param    string         $sourcePath         Source image path
     * @param    string|null    $targetPath         Destination image path
     * @param    null           $outputMimeType
     * @param    integer        $srcX               x-coordinate of source point
     * @param    integer        $srcY               y-coordinate of source point
     * @param    integer        $dstW               Destination width
     * @param    integer        $dstH               Destination height
     * @param    integer        $srcW               Source width
     * @param    integer        $srcH               Source height
     * @param    integer        $quality
     * @param    boolean        $overwrite
     * @return   string
     * @throws   \Exception
     */
    public static function resizeImage($sourcePath, $targetPath = null, $outputMimeType=null, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH, $quality = 75, $overwrite = false)
    {
        // round sizes
        $srcX = ceil($srcX);
        $srcY = ceil($srcY);
        $dstW = ceil($dstW);
        $dstH = ceil($dstH);
        $srcW = ceil($srcW);
        $srcH = ceil($srcH);

        // if there isn't destination path, the source path is overwritten
        $targetPath  = ($targetPath? $targetPath : $sourcePath);

        // blank destination image is created
        $targetImage = imagecreatetruecolor($dstW, $dstH);

        // find out mime type of image
        $finfo  = new \finfo(FILEINFO_MIME_TYPE);
        $mime   = $finfo->file($sourcePath);

        // the image is created with different functions, depending of mime type
        switch($mime)
        {
            case 'image/gif':
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                imagealphablending($targetImage, false);
                imagesavealpha($targetImage, true);
                $sourceImage = imagecreatefrompng($sourcePath);
                imagealphablending($sourceImage, true);
                break;
        }

        // we create images
        imagecopyresampled($targetImage, $sourceImage, 0, 0, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);

        // get info about target path
        $pathInfo = pathinfo($targetPath);

        // set extension if we have to change
        if(($outputMimeType === "image/gif" || $outputMimeType === "image/jpeg" || $outputMimeType === "image/png") && !empty($pathInfo['extension']))
        {
            // set new mime type
            $mime = $outputMimeType;

            //set output mime type
            switch ($mime)
            {
                case 'image/gif':
                    $extension = 'gif';
                    break;
                case 'image/jpeg':
                    $extension = 'jpg';
                    break;
                case 'image/png':
                    $extension = 'png';
                    break;
            }
            $targetPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.' . $extension;
        }

        // I have in mind false string, in case to receive values across ajax
        if($overwrite == 'false' || !$overwrite)
        {
            // if file already exists, a number is added
            $i = 0;
            while (file_exists($targetPath))
            {
                $i++;
                if(empty($pathInfo['extension']))
                {
                    $fileName = $pathInfo['filename'] . '-' . $i;
                }
                else
                {
                    $fileName = $pathInfo['filename'] . '-' . $i . '.' . $extension;
                }
                // set new target path
                $targetPath = $pathInfo['dirname'] . '/' . $fileName;
            }
        }

        // The filename ending depends on the image type
        switch ($mime)
        {
            case 'image/gif':
                if(!imagegif($targetImage, $targetPath))
                    throw new \Exception("Error, the image " . $sourcePath . " could not be copied to the directory: " . $targetPath);
                break;
            case 'image/jpeg':
                if(!imagejpeg($targetImage, $targetPath, intval($quality)))
                    throw new \Exception("Error, the image " . $sourcePath . " could not be copied to the directory: " . $targetPath);
                break;
            case 'image/png':
                if(!imagepng($targetImage, $targetPath))
                    throw new \Exception("Error, the image " . $sourcePath . " could not be copied to the directory: " . $targetPath);
                break;
        }

        $pathInfo = pathinfo($targetPath);
        return $pathInfo['basename'];
    }

    /**
     * Function to store attachment elements
     *
     * @access  public
     * @param   string  $path
     * @return  bool
     * @throws  \Exception
     */
    public static function iOSCheckOrientation($path)
    {
        if(!function_exists('exif_read_data'))
        {
            throw new \Exception("Error, PHP EXIF module isn't activated, this module is necessary to set correct image orientation from iOS device");
        }

        // get mime type
        $finfo  = new \finfo(FILEINFO_MIME_TYPE);
        $mime   = $finfo->file($path);

        if($mime == 'image/jpeg')
        {
            // Only support to jpeg and tiff
            $exif = exif_read_data($path);

            if(!empty($exif['Orientation']))
            {
                $image = imagecreatefromstring(file_get_contents($path));
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
                imagejpeg($image, $path);
            }
        }
    }

    /**
     * Function to get icon image from file
     *
     * @access  public
     * @param   string  $mime
     * @return  array
     */
    public static function getMimeIconImage($mime)
    {
        switch ($mime) {
            case 'image/gif':
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/png':
            case 'image/svg+xml':
                return [ 'id' => 1, 'name' => trans_choice('pulsar::pulsar.image', 1), 'icon' => 'icon_Generic.png'];
                break;
            case 'text/plain':
            case 'application/msword':
                return [ 'id' => 2, 'name' => trans_choice('pulsar::pulsar.file', 1), 'icon' => 'icon_DOCX.png'];
                break;
            case 'application/x-pdf':
            case 'application/pdf':
                return [ 'id' => 2, 'name' => trans_choice('pulsar::pulsar.file', 1), 'icon' => 'icon_PDF.png'];
                break;
            case 'application/zip':
                return [ 'id' => 2, 'name' => trans_choice('pulsar::pulsar.file', 1), 'icon' => 'icon_ZIP.png'];
                break;
            case 'video/avi':
            case 'video/mpeg':
            case 'video/quicktime':
            case 'video/mp4':
                return [ 'id' => 3, 'name' => trans_choice('pulsar::pulsar.video', 1), 'icon' => 'icon_Generic.png'];
                break;
            default:
                return [ 'id' => 2, 'name' => trans_choice('pulsar::pulsar.file', 1), 'icon' => 'icon_Generic.png'];
        }
    }
}