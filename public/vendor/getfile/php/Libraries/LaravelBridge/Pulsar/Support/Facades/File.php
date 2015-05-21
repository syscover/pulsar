<?php

namespace Pulsar\Support\Facades;

class File 
{
    
    private $file;
    
    public function __construct($file)
    {
        $this->file = $file;
    }
    
    public function getSize()
    {
        return $this->file['size'];
    }
    
    public function getClientOriginalName()
    {
        return $this->file['name'];
    }
    
    public function getClientOriginalExtension()
    {
        return pathinfo($this->getClientOriginalName(), PATHINFO_EXTENSION);
    }
    
    public function getMimeType()
    {
        if(function_exists('finfo_file'))
        {
            $var = explode(";",finfo_file(finfo_open(FILEINFO_MIME),  $this->file['tmp_name']));
            return reset($var);
        }
        elseif(function_exists('mime_content_type'))
        {
            return mime_content_type($this->file['tmp_name']);
        }
        else
        {
            return $this->file['type'];
        }
    }

    public function getRealPath()
    {
        return $this->file['tmp_name'];
    }
    
    public function move($path, $filename)
    {
        move_uploaded_file($this->file['tmp_name'],$path.'/'.$filename);
    }
}