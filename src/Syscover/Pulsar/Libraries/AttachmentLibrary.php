<?php namespace Syscover\Pulsar\Libraries;

use Illuminate\Support\Facades\File;
use Syscover\Pulsar\Exceptions\InvalidArgumentException;
use Syscover\Pulsar\Models\Attachment;

class AttachmentLibrary {

    /**
     *  Function to store attachment elements
     *
     * @access	public
     * @param   \Illuminate\Support\Facades\Request     $attachments
     * @param   string                                  $lang
     * @param   string                                  $routesConfigFile
     * @param   integer                                 $objectId
     * @param   string                                  $resource
     * @return  boolean
     */
    public static function storeAttachments($attachments, $routesConfigFile, $resource, $objectId, $lang)
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
                list($width, $height) = getimagesize(public_path() . $attachment->folder . '/' . $attachment->tmpFileName);
            }
            
            // move file fom temp file to attachment folder
            File::move(public_path() . $attachment->folder . '/' . $attachment->tmpFileName, public_path() . config($routesConfigFile . '.attachmentFolder') . '/' . $objectId . '/'. $lang .'/' . $attachment->fileName);

            Attachment::create([
                'id_016'                => $idAttachment,
                'lang_id_016'           => $lang,
                'resource_id_016'       => $resource,
                'object_id_016'         => $objectId,
                'family_id_016'         => empty($attachment->family)? null : $attachment->family,
                'library_id_016'        => $attachment->library,
                'library_file_name_016' => empty($attachment->libraryFileName)? null : $attachment->libraryFileName,
                'sorting_016'           => $attachment->sorting,
                //'url_016'               => $attachment->url, videos??
                'name_016'              => empty($attachment->name)? null : $attachment->name,
                'file_name_016'         => empty($attachment->fileName)? null : $attachment->fileName,
                'mime_016'              => $attachment->mime,
                'size_016'              => filesize(public_path() . config($routesConfigFile . '.attachmentFolder') . '/' . $objectId .  '/' . $lang . '/' . $attachment->fileName),
                'type_id_016'           => $attachment->type->id,
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
     * @param   string      $routesConfigFile
     * @param   string      $resource
     * @param   integer     $objectId
     * @param   string      $lang
     * @param   boolean     $copyAttachment
     * @return  array       $response
     */
    public static function getRecords($routesConfigFile, $resource, $objectId, $lang, $copyAttachment = false)
    {
        $response['attachments'] = Attachment::getRecords([
            'lang_id_016'       => $lang,
            'resource_id_016'   => $resource,
            'object_id_016'     => $objectId,
            'orderBy'           => ['column' => 'sorting_016', 'order' => 'asc']
        ]);
        $attachmentsInput = [];

        foreach($response['attachments'] as &$attachment)
        {
            $tmpFileName = null;

            if($copyAttachment)
            {
                // function to duplicate files if we create a new lang object
                // copy attachments base lang article to temp folder
                $tmpFileName = uniqid();
                File::copy(public_path() . config($routesConfigFile . '.attachmentFolder') . '/' . $objectId . '/' . session('baseLang')->id_001 . '/' . $attachment->file_name_016, public_path() . config($routesConfigFile . '.tmpFolder') . '/' . $tmpFileName);
                // store tmp file name in attachment to know temporal name
                $attachment['tmp_file_name_016'] = $tmpFileName;
            }

            // get json data from attachment
            $attachmentData = json_decode($attachment->data_016);

            $attachmentsInput[] = [
                'id'                => $attachment->id_016,
                'family'            => $attachment->family_id_016,
                'type'              => ['id' => $attachment->type_id_016, 'name' => $attachment->type_text_016, 'icon' => $attachmentData->icon],
                'mime'              => $attachment->mime_016,
                'name'              => $attachment->name_016,
                'folder'            => $copyAttachment? config($routesConfigFile . '.tmpFolder') : config($routesConfigFile . '.attachmentFolder') . '/' . $attachment->object_id_016 . '/' . $attachment->lang_id_016,
                'tmpFileName'       => $tmpFileName,
                'fileName'          => $attachment->file_name_016,
                'width'             => $attachment->width_016,
                'height'            => $attachment->height_016,
                'library'           => $attachment->library_id_016,
                'libraryFileName'   => $attachment->library_file_name_016,
                'sorting'           => $attachment->sorting_016,
            ];
        }

        $response['attachmentsInput'] = json_encode($attachmentsInput);

        return $response;
    }

    /**
     *  Function to delete attachment
     *
     * @access	public
     * @param   string      $routesConfigFile
     * @param   string      $resource
     * @param   integer     $objectId
     * @param   string      $lang
     * @return  boolean     $response
     */
    public static function deleteAttachment($routesConfigFile, $resource, $objectId, $lang = null)
    {
        Attachment::deleteAttachment([
            'lang_id_016'       => $lang,
            'resource_id_016'   => $resource,
            'object_id_016'     => $objectId
        ]);

        if(isset($lang))
        {
            if(!empty($objectId) &&  !empty($lang))
            {
                // delete all attachments from this object
                $response = File::deleteDirectory(public_path() . config($routesConfigFile . '.attachmentFolder') . '/' . $objectId. '/' . $lang);
            }
            else
            {
                throw new InvalidArgumentException('Object Id, is not defined to delete attachment files');
            }

        }
        else
        {
            if(!empty($objectId))
            {
                // delete all attachments from this object
                $response = File::deleteDirectory(public_path() . config($routesConfigFile . '.attachmentFolder') . '/' . $objectId);
            }
            else
            {
                throw new InvalidArgumentException('Object Id, is not defined to delete attachment files');
            }
        }

        return $response;
    }
}