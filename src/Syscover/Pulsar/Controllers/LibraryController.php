<?php namespace Syscover\Pulsar\Controllers;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request as HttpRequest;
use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\Library;

class LibraryController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'Library';
    protected $folder       = 'library';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_014', ['type' => 'library_img', 'data' => 'file_name_014'], 'file_name_014', ['type' => 'size', 'data' => 'size_014'], 'mime_014', 'type_text_014'];
    protected $nameM        = 'file_014';
    protected $model        = '\Syscover\Pulsar\Models\Library';
    protected $icon         = 'fa fa-book';
    protected $objectTrans  = 'library';
    protected $jsonParam    = ['edit' => false];

    public function customColumnType($row, $aColumn, $aObject, $request)
    {
        switch ($aColumn['type'])
        {
            case 'library_img':
                if($aObject['type_014'] == 1)
                {
                    $row[] = '<img src="' . asset(config('hotels.libraryFolder') . '/' . $aObject['file_name_014']) . '" class="image-index-list">';
                }
                else
                {
                    $data = json_decode($aObject['data_014']);
                    $row[] = '<img src="' . asset(config('hotels.iconsFolder') . '/' . $data->icon) . '" class="image-index-list">';
                }

                break;
            case 'size':
                $row[] = number_format($aObject['size_014'] / 1048576, 2) . ' Mb';
                break;
        }

        return $row;
    }


    public function storeLibrary(HttpRequest $request)
    {
        $parameters         = $request->route()->parameters();
        $files              = $request->input('files');
        $objects            = [];
        $objectsResponse    = [];
        $filesNames         = [];

        foreach($files as $file)
        {
            $tmpFileName = uniqid();
            File::copy(public_path() . config($request->input('routesConfigFile') . '.libraryFolder') . '/' . $file['name'], public_path() . config($request->input('routesConfigFile') . '.tmpFolder') . '/' . $tmpFileName);

            $width = null; $height= null;
            if($file['isImage'] == 'true')
            {
                list($width, $height) = getimagesize(public_path() . config($request->input('routesConfigFile') . '.libraryFolder') . '/' . $file['name']);
            }

            $type = $this->getType($file['mime']);

            $objects[] = [
                'resource_014'  => $request->input('resource'),
                'url_014'       => null,
                'file_name_014' => $file['name'],
                'mime_014'      => $file['mime'],
                'size_014'      => $file['size'],
                'type_014'      => $type['id'],
                'type_text_014' => $type['name'],
                'width_014'     => $width,
                'height_014'    => $height,
                'data_014'      => json_encode(['icon' => $type['icon']])
            ];

            if($file['name'] != null && $file['name'] != "")
            {
                $filesNames[] = $file['name'];
            }

            // convert json format to store in document DOM
            $objectsResponse[] = [
                'id'            => null,
                'family'        => null,
                'type'          => $type,
                'mime'          => $file['mime'],
                'url'           => null,
                'name'          => null,
                'folder'        => config($request->input('routesConfigFile') . '.tmpFolder'),
                'tmpFileName'   => $tmpFileName,
                'fileName'      => $file['name'],
                'width'         => $width,
                'height'        => $height
            ];
        }

        Library::insert($objects);

        $lastLibraryInsert = Library::whereIn('file_name_014', $filesNames)->get();

        foreach($lastLibraryInsert as $library)
        {
            foreach($objectsResponse as &$objectResponse)
            {
                if($library->file_name_014 == $objectResponse['fileName'])
                {
                    $objectResponse['library']          = $library->id_014;
                    $objectResponse['libraryFileName']  = $library->file_name_014;
                }
            }
        }

        $response = [
            'success' => true,
            'files'   => $objectsResponse
        ];

        return response()->json($response);
    }

    /*
    public function deleteCustomRecord($object)
    {
        File::delete(public_path() . config('hotels.libraryFolder') . '/' . $object->file_name_014);
    }

    public function deleteCustomRecords($ids)
    {
        $files      = Library::whereIn('id_014', $ids)->get();
        $fileNames  = [];

        foreach($files as $file)
        {
            $fileNames[] = public_path() . config('hotels.libraryFolder') . '/' . $file->file_name_014;
        }

        File::delete($fileNames);
    }
    */

    private function getType($mime)
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
            case 'video/avi':
            case 'video/mpeg':
            case 'video/quicktime':
            case 'video/mp4':
                return [ 'id' => 3, 'name' => trans_choice('pulsar::pulsar.video', 1), 'icon' => 'icon_Generic.png'];
                break;
            default:
                return null;
        }
    }
}