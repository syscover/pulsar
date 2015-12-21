<?php

/**
 * @package		Pulsar
 * @author		Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2013, SYSCOVER, SL.
 * @license		
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource	Auxiliary file library
 */
// ------------------------------------------------------------------------

namespace Libraries;

use Pulsar\Support\Facades\Input,
    Pulsar\Support\Facades\File;

class FileManager 
{

    /**
     *  Perform file upload
     *
     * @access	public
     * @return	boolean
     */
    public static function uploadFiles($inputName, $path, $encryption = false, $newFilename = false, $overwrite = false)
    {
        $file           = Input::file($inputName);                  // Instance object File
        $extension      = $file->getClientOriginalExtension();
        $filename       = $file->getClientOriginalName();
        $filenameOrg    = $filename;
        
        if ($encryption)
        {
            mt_srand();
            $filename = md5(uniqid(mt_rand())) . "." . $extension;
        } 
        elseif ($newFilename)
        {
            $filename = $newFilename . "." . $extension;
        }

        if(!$overwrite)
        {
            $i = 0;
            while (file_exists($path . '/' . $filename))
            {
                $i++;

                if(!$encryption && !$newFilename)							// New name is generated from the original file name
                {
                    $baseName = basename($filenameOrg, '.' . $extension);
                    $filename = $baseName . '-' . $i . '.' . $extension;
                }
                elseif(!$encryption && $newFilename)						// New name is generated from the provided name
                {
                    $filename = $newFilename . '-' . $i . '.' . $extension;
                }
                else														// New name is generated using encryption
                {
                    mt_srand();
                    $filename = md5(uniqid(mt_rand())) . "." . $extension;
                }
            }
        }

        $file->move($path, $filename);

        return $filename;
    }

    /**
     *  Returns file extension
     *
     * @access	public
     * @return	string
     */
    public static function getFileExtension($filename)
    {
        return pathinfo(strtolower($filename), PATHINFO_EXTENSION);
    }
    
    /**
    * 	Checks that a file matches one of the MIME types provided in the array
    *
    *  @param 	string  		$mime           MIME to check
    *  @param 	array			$mimes          MIME type array
    *  @return  boolean
    */
    public static function isMimeTypeAllowed($mime, $mimes)
    {
        
        if (in_array($mime, $mimes))
        {
            return true;
        }
                    
        foreach ($mimes as $patterMime)
        {
            $strPos = strpos($patterMime,'*');
            if ($strPos !== false) 
            {
                $mimeCompare = substr($patterMime, 0, $strPos-1); // Get the MIME type without "*"
                if(substr($mime, 0, $strPos-1) === $mimeCompare)
                {
                    return true;
                }
            }
        }
        
        return false;
    }
}