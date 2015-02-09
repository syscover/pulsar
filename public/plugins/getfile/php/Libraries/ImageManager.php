<?php

namespace Libraries;

use Libraries\FileManager;

class ImageManager 
{
    
    /**
    * 	Resize image
    *
    *  @param 	string  		$srcPath 	Source image path
    *  @param 	string|null		$dstPath 	Destination image path
    *  @param 	integer  		$srcX 		x-coordinate of source point
    *  @param 	integer  		$srcY 		y-coordinate of source point
    *  @param 	integer  		$dstW 		Destination width
    *  @param 	integer  		$dstH 		Destination height
    *  @param 	integer  		$srcW 		Source width
    *  @param 	integer  		$srcH 		Source height
    *  @return 	string
    */
    public static function resizeImage($srcPath, $dstPath = null, $outputExtension=null, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH) 
    {
        $srcX = ceil($srcX);
        $srcY = ceil($srcY);
        $dstW = ceil($dstW);
        $dstH = ceil($dstH);
        $srcW = ceil($srcW);
        $srcH = ceil($srcH);
        
        $dstPath  = ($dstPath ? $dstPath : $srcPath);					// If there is no destination path, the source path is overwritten
        
        $dstImage = imagecreatetruecolor($dstW, $dstH);					// Blank destination image is created
        
        $extension = FileManager::getFileExtension($srcPath);			// Find out the extension of the destination image
                
        switch ($extension)												// The image is created with different functions, depending on the extension
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
                
                //imagecreatefrompng($srcPath);
            break;
        }
                
        imagecopyresampled($dstImage, $srcImage, 0, 0, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
           
        $pathInfo = pathinfo($dstPath);

		$fileName = null;
        if($outputExtension === "gif" || $outputExtension === "jpeg" || $outputExtension === "jpg" || $outputExtension === "png" ){
            
            $extension = $outputExtension;          
            
            $dstPath = $pathInfo['dirname'].'/'.$pathInfo['filename'].'.'.$outputExtension;
            
            $fileName = $pathInfo['filename'].'.'.$outputExtension;
        }
        else
        { 
            $fileName = $pathInfo['basename'];
        }
        
        switch ($extension)												// The filename ending depends on the image type
        {
            case 'gif':
                imagegif($dstImage, $dstPath);
            break;
            case 'jpeg':
            case 'jpg':
                imagejpeg($dstImage, $dstPath);
            break;
            case 'png':
                imagepng($dstImage, $dstPath);
            break;
       }
       
       return $fileName;
    }
}