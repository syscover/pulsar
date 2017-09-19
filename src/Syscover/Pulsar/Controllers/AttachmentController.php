<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Support\Facades\DB;
use Syscover\Pulsar\Core\Controller;
use Illuminate\Support\Facades\File;
use Syscover\Pulsar\Models\Attachment;

/**
 * Class AttachmentController
 * @package Syscover\Pulsar\Controllers
 */

class AttachmentController extends Controller
{
    public function storeAttachment()
    {
        $parameters             = $this->request->route()->parameters();
        $attachments            = $this->request->input('attachments');
        $attachmentsResponse    = [];

        foreach($attachments as $attachment)
        {
            $idAttachment = Attachment::max('id_016');
            $idAttachment++;

            // move file from temp file to attachment folder
            File::move(public_path() . config($this->request->input('routesConfigFile') . '.tmpFolder') . '/' . $attachment['tmpFileName'], public_path() . config($this->request->input('routesConfigFile') . '.attachmentFolder') . '/' . $parameters['object'] . '/' . $parameters['lang'] . '/' . $attachment['fileName']);

            $attachmentsResponse[] = Attachment::create([
                'id_016'                => $idAttachment,
                'lang_id_016'           => $parameters['lang'],
                'resource_id_016'       => $this->request->input('resource'),
                'object_id_016'         => $parameters['object'],
                'family_id_016'         => null,
                'library_id_016'        => $attachment['library'],
                'library_file_name_016' => $attachment['libraryFileName'],
                'sorting_016'           => null,
                'name_016'              => null,
                'file_name_016'         => $attachment['fileName'],
                'mime_016'              => $attachment['mime'],
                'size_016'              => filesize(public_path() . config($this->request->input('routesConfigFile') . '.attachmentFolder') . '/' . $parameters['object'] . '/' . $parameters['lang'] . '/' . $attachment['fileName']),
                'type_id_016'           => $attachment['type']['id'],
                'type_text_016'         => $attachment['type']['name'],
                'width_016'             => empty($attachment['width'])? null : $attachment['width'],
                'height_016'            => empty($attachment['height'])? null : $attachment['height'],
                'data_016'              => json_encode(['icon' => $attachment['type']['icon']])
            ]);
        }

        $response = [
            'success'       => true,
            'attachments'   => $attachmentsResponse
        ];

        return response()->json($response);
    }

    public function apiUpdateAttachment()
    {
        $parameters = $this->request->route()->parameters();
        $attachment = $this->request->input('attachment');

        // check that is a attachment stored
        // lso we control the edit action is because,
        // when creating a new language id detects and assumes that the image is in the database
        if(isset($attachment['id']) && $this->request->input('action') == 'update')
        {
            $width = null; $height= null;
            if($attachment['type']['id'] == 1)
            {
                list($width, $height) = getimagesize(public_path() . $attachment['folder'] . '/' . $attachment['fileName']);
            }

            Attachment::where('id_016', $attachment['id'])->where('lang_id_016', $parameters['lang'])->update([
                'family_id_016'         => $attachment['family'] == ""? null : $attachment['family'],
                'library_id_016'        => empty($attachment['library'])? null : $attachment['library'],
                'library_file_name_016' => empty($attachment['libraryFileName'])? null : $attachment['libraryFileName'],
                'sorting_016'           => $attachment['sorting'],
                //'url_016'               => $attachment['url'] == ""? null : $attachment['url'],
                'name_016'              => $attachment['name'] == ""? null : $attachment['name'],
                'file_name_016'         => $attachment['fileName'] == ""? null : $attachment['fileName'],
                'mime_016'              => $attachment['mime'],
                'size_016'              => filesize(public_path() . config($this->request->input('routesConfigFile') . '.attachmentFolder') . '/' . $parameters['object'] . '/' . $parameters['lang'] . '/' . $attachment['fileName']),
                'type_id_016'           => $attachment['type']['id'],
                'type_text_016'         => $attachment['type']['name'],
                'width_016'             => $width,
                'height_016'            => $height
            ]);
        }

        $response = [
            'success'   => true,
            'message'   => "Attachment updated",
            'function'  => "apiUpdateAttachment"
        ];

        return response()->json($response);
    }

    public function apiUpdatesAttachment()
    {
        $parameters     = $this->request->route()->parameters();
        $attachments    = $this->request->input('attachments');

        foreach($attachments as $attachment)
        {
            // check that is a attachment stored also we control is a edit action because,
            // when creating a new language id detects and assumes that the image is in the database
            if(isset($attachment['id']) && $this->request->input('action') == 'update')
            {
                $width = null; $height= null;
                // attachment type 1 = image, 2 = file, 3 = video
                if($attachment['type']['id'] == 1)
                {
                    list($width, $height) = getimagesize(public_path() . $attachment['folder'] . '/' . $attachment['fileName']);
                }

                Attachment::where('id_016', $attachment['id'])->where('lang_id_016', $parameters['lang'])->update([
                    'family_id_016'         => $attachment['family'] == ""? null : $attachment['family'],
                    'library_id_016'        => empty($attachment['library'])? null : $attachment['library'],
                    'library_file_name_016' => empty($attachment['libraryFileName'])? null : $attachment['libraryFileName'],
                    'sorting_016'           => $attachment['sorting'],
                    //'url_016'               => $attachment['url'] == ""? null : $attachment['url'],
                    'name_016'              => $attachment['name'] == ""? null : $attachment['name'],
                    'file_name_016'         => $attachment['fileName'] == ""? null : $attachment['fileName'],
                    'mime_016'              => $attachment['mime'],
                    'size_016'              => filesize(public_path() . config($this->request->input('routesConfigFile') . '.attachmentFolder') . '/' . $parameters['object'] . '/' . $parameters['lang'] . '/' . $attachment['fileName']),
                    'type_id_016'           => $attachment['type']['id'],
                    'type_text_016'         => $attachment['type']['name'],
                    'width_016'             => $width,
                    'height_016'            => $height
                ]);
            }
        }

        $response = [
            'success'   => true,
            'message'   => "Attachments updated",
            'function'  => "apiUpdatesAttachment"
        ];

        return response()->json($response);
    }

    public function apiSortingUpdatesAttachment()
    {
        $parameters     = $this->request->route()->parameters();
        $attachments    = collect($this->request->input('attachments'));

        DB::select(DB::connection('mysql2')->raw("SET @x = 0;"));
        DB::select(DB::connection('mysql2')->raw("                
                UPDATE 001_016_attachment 
                    SET sorting_016 = (@x:=@x+1) 
                    WHERE lang_id_016 = '" . $parameters['lang'] . "' 
                    AND id_016 IN (" . $attachments->implode(',') . ") 
                    ORDER BY FIELD(id_016, " . $attachments->implode(',') . ");
           "));

        $response = [
            'success'   => true,
            'message'   => "Attachments sorting updated",
            'function'  => "apiSortingUpdatesAttachment"
        ];

        return response()->json($response);
    }

    public function apiDeleteAttachment()
    {
        $parameters = $this->request->route()->parameters();

        $attachment = Attachment::getTranslationRecord(['id' => $parameters['id'], 'lang' => $parameters['lang']]);

        if($attachment->file_name_016 != null && $attachment->file_name_016 != "")
        {
            File::delete(public_path() . config($this->request->input('routesConfigFile') . '.attachmentFolder') . '/' . $attachment->object_id_016 . '/' . $attachment->lang_id_016 . '/' . $attachment->file_name_016);
        }

        Attachment::deleteTranslationRecord($parameters);

        $response = [
            'success'   => true,
            'message'   => "Attachment deleted",
            'function'  => "apiDeleteAttachment"
        ];

        return response()->json($response);
    }

    public function apiDeleteTmpAttachment()
    {
        File::delete(public_path() . config($this->request->input('routesConfigFile') . '.tmpFolder') . '/' . $this->request->input('fileName'));

        $response = [
            'success'   => true,
            'message'   => "Temp attachment deleted",
            'function'  => "apiDeleteTmpAttachment"
        ];

        return response()->json($response);
    }
}