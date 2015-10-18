<?php

namespace Libraries;

use Libraries\FileManager;

class ImageManager 
{
    
    /**
    * 	Resize image
    *
    *  @param 	string  		$srcPath 	        Source image path
    *  @param 	string|null		$dstPath 	        Destination image path
    *  @param 	string|null		$outputExtension 	Output extension
    *  @param 	integer  		$srcX 		        x-coordinate of source point
    *  @param 	integer  		$srcY 		        y-coordinate of source point
    *  @param 	integer  		$dstW 		        Destination width
    *  @param 	integer  		$dstH 		        Destination height
    *  @param 	integer  		$srcW 		        Source width
    *  @param 	integer  		$srcH 		        Source height
    *  @return 	string
    */
    public static function resizeImage($srcPath, $dstPath = null, $outputExtension=null, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH, $quality = 75, $overwrite = false)
    {
        $srcX = ceil($srcX);
        $srcY = ceil($srcY);
        $dstW = ceil($dstW);
        $dstH = ceil($dstH);
        $srcW = ceil($srcW);
        $srcH = ceil($srcH);

        // If there is no destination path, the source path is overwritten
        $dstPath  = ($dstPath ? $dstPath : $srcPath);

        // Blank destination image is created
        $dstImage = imagecreatetruecolor($dstW, $dstH);

        // Find out the extension of the destination image
        $extension = FileManager::getFileExtension($srcPath);

        // The image is created with different functions, depending on the extension
        switch ($extension)
        {
            case 'gif':
                $srcImage = imagecreatefromgif($srcPath); 
            break;
            case 'jpeg':
            case 'jpg':
                $srcImage = imagecreatefromjpeg($srcPath); 
            break;
            case 'png':
                imagealphablending($dstImage, false);
                imagesavealpha($dstImage, true);  
                $srcImage = imagecreatefrompng($srcPath);
                imagealphablending($srcImage, true);
            break;
        }

        imagecopyresampled($dstImage, $srcImage, 0, 0, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);

        $pathInfo = pathinfo($dstPath);

		$fileName = null;

        if($outputExtension === "gif" || $outputExtension === "jpeg" || $outputExtension === "jpg" || $outputExtension === "png" ){

            // set new extension
            $extension = $outputExtension;          
            
            $dstPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.' . $outputExtension;
            
            $fileName = $pathInfo['filename'] . '.' . $outputExtension;
        }
        else
        {
            // set extension to check if exist file
            $extension = $pathInfo['extension'];

            $fileName = $pathInfo['basename'];
        }

        if($overwrite == 'false' || !$overwrite)
        {
            // if file already exists, a number is added
            $i = 0;
            while (file_exists($dstPath))
            {
                $i++;
                // if exist file, rewrite filename
                $fileName = $pathInfo['filename'] . '-' . $i . '.' . $extension;

                $dstPath = $pathInfo['dirname'] . '/' . $fileName;
            }
        }

        // The filename ending depends on the image type
        switch ($extension)
        {
            case 'gif':
                if(!imagegif($dstImage, $dstPath)) throw new \Exception("Error, the image " . $fileName . " could not be copied to the directory: " . $dstPath);
            break;
            case 'jpeg':
            case 'jpg':
                if(!imagejpeg($dstImage, $dstPath, intval($quality))) throw new \Exception("Error, the image " . $fileName . " could not be copied to the directory: " . $dstPath);
            break;
            case 'png':
                if(!imagepng($dstImage, $dstPath)) throw new \Exception("Error, the image " . $fileName . " could not be copied to the directory: " . $dstPath);
            break;
        }

        return $fileName;
    }
}