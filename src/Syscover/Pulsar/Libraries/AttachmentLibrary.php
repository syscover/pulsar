<?php namespace Syscover\Pulsar\Libraries;

/**
 * @package		Pulsar
 * @author		Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL.
 * @license
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource  Librarie that instance helper functions to attachments
 */

use Illuminate\Support\Facades\File;
use Syscover\Pulsar\Models\Attachment;

class AttachmentLibrary {

    /**
     *  Function to store attachment elements
     *
     * @access	public
     * @param   \Illuminate\Support\Facades\Request   $attachments
     * @param   string      $lang
     * @param   string      $routesConfigFile
     * @param   integer     $objectId
     * @param   string      $resource
     * @return  boolean
     */
    public static function storeAttachments($attachments, $lang, $routesConfigFile, $resource, $objectId)
    {
        if(!File::exists(public_path() . config($routesConfigFile . '.attachmentFolder') . '/' . $objectId . '/'. $lang))
        {
            File::makeDirectory(public_path() . config($routesConfigFile . '.attachmentFolder') . '/' . $objectId . '/'. $lang, 0755, true);
        }

        foreach($attachments as $attachment)
        {
            $idAttachment = Attachment::max('id_016');
            $idAttachment++;

            $width = null; $height= null;
            if($attachment->type->id == 1)
            {
                list($width, $height) = getimagesize(public_path() . $attachment->folder . '/' . $attachment->fileName);
            }
            
            // move file fom temp file to attachment folder
            File::move(public_path() . $attachment->folder . '/' . $attachment->fileName, public_path() . config($routesConfigFile . '.attachmentFolder') . '/' . $objectId . '/'. $lang .'/' . $attachment->fileName);

            Attachment::create([
                'id_016'                => $idAttachment,
                'lang_016'              => $lang,
                'resource_016'          => $resource,
                'object_016'            => $objectId,
                'family_016'            => $attachment->family == ""? null : $attachment->family,
                'library_016'           => $attachment->library,
                'library_file_name_016' => $attachment->libraryFileName == ""? null : $attachment->libraryFileName,
                'sorting_016'           => $attachment->sorting,
                'url_016'               => $attachment->url,
                'name_016'              => $attachment->name == ""? null : $attachment->name,
                'file_name_016'         => $attachment->fileName == ""? null : $attachment->fileName,
                'mime_016'              => $attachment->mime,
                'size_016'              => filesize(public_path() . config($routesConfigFile . '.attachmentFolder') . '/' . $objectId .  '/' . $lang . '/' . $attachment->fileName),
                'type_016'              => $attachment->type->id,
                'type_text_016'         => $attachment->type->name,
                'width_016'             => $width,
                'height_016'            => $height,
                'data_016'              => json_encode(['icon' => $attachment->type->icon])
            ]);
        }
    }

    /**
     *  Function to get attachment element with json string to new element
     *
     * @access	public
     * @param   string      $lang
     * @param   string      $routesConfigFile
     * @param   integer     $objectId
     * @param   string      $resource
     * @param   boolean     $copyAttachment
     * @return  array       $response
     */
    public static function getAttachments($lang, $routesConfigFile, $resource, $objectId, $copyAttachment = false)
    {
        $response['attachments'] = Attachment::getAttachments([
            'lang_016'      => $lang,
            'resource_016'  => $resource,
            'object_016'    => $objectId
        ]);
        $attachmentsInput = [];

        foreach($response['attachments'] as $attachment)
        {
            if($copyAttachment)
            {
                // Copy attachments base lang article to temp folder
                File::copy(public_path() . config($routesConfigFile . '.attachmentFolder') . '/' . $objectId . '/' . session('baseLang')->id_001 . '/' . $attachment->file_name_016, public_path() . config($routesConfigFile . '.tmpFolder') . '/' . $attachment->file_name_016);
            }

            // get json data from attachment
            $attachmentData = json_decode($attachment->data_016);

            $attachmentsInput[] = [
                'id'                => $attachment->id_016,
                'family'            => $attachment->family_016,
                'type'              => ['id' => $attachment->type_016, 'name' => $attachment->type_text_016, 'icon' => $attachmentData->icon],
                'mime'              => $attachment->mime_016,
                'name'              => $attachment->name_016,
                'folder'            => $copyAttachment? config($routesConfigFile . '.tmpFolder') : config($routesConfigFile . '.attachmentFolder') . '/' . $attachment->object_016 . '/' . $attachment->lang_016,
                'fileName'          => $attachment->file_name_016,
                'width'             => $attachment->width_016,
                'height'            => $attachment->height_016,
                'library'           => $attachment->library_016,
                'libraryFileName'   => $attachment->library_file_name_016,
                'sorting'           => $attachment->sorting_016,
            ];
        }

        $response['attachmentsInput'] = json_encode($attachmentsInput);

        return $response;
    }
}