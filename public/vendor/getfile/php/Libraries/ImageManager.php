<?php

namespace Libraries;

use Libraries\FileManager;

class ImageManager 
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
}