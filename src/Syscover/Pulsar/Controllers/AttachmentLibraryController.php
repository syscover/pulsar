<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request as HttpRequest;
use Syscover\Pulsar\Libraries\ImageManagerLibrary;
use Syscover\Pulsar\Traits\TraitController;
use Syscover\Pulsar\Models\AttachmentLibrary;

/**
 * Class AttachmentLibraryController
 * @package Syscover\Pulsar\Controllers
 */

class AttachmentLibraryController extends Controller {

    use TraitController;

    protected $routeSuffix  = 'attachmentLibrary';
    protected $folder       = 'attachment_library';
    protected $package      = 'pulsar';
    protected $aColumns     = ['id_014', ['type' => 'library_img', 'data' => 'file_name_014'], 'file_name_014', ['type' => 'size', 'data' => 'size_014'], 'mime_014', 'type_text_014'];
    protected $nameM        = 'file_014';
    protected $model        = AttachmentLibrary::class;
    protected $icon         = 'fa fa-book';
    protected $objectTrans  = 'library';
    protected $viewParameters   = [
        'showButton'    => false,
        'editButton'    => false,
        'deleteButton'  => true
    ];

    public function customColumnType($request, $row, $aColumn, $aObject)
    {
        switch ($aColumn['type'])
        {
            case 'library_img':
                if($aObject['type_014'] == 1)
                {
                    $row[] = '<img src="' . asset(config($aObject['folder_012'] . '.libraryFolder') . '/' . $aObject['file_name_014']) . '" class="image-index-list">';
                }
                else
                {
                    $data = json_decode($aObject['data_014']);
                    $row[] = '<img src="' . asset(config('.iconsFolder') . '/' . $data->icon) . '" class="image-index-list">';
                }

                break;
            case 'size':
                $row[] = number_format($aObject['size_014'] / 1048576, 2) . ' Mb';
                break;
        }

        return $row;
    }


    public function storeAttachmentLibrary(HttpRequest $request)
    {
        $parameters         = $request->route()->parameters();
        $files              = $request->input('files');
        $objects            = [];
        $objectsResponse    = [];
        $filesNames         = [];

        foreach($files as $file)
        {
            // create tmp name
            $tmpFileName = uniqid();
            File::copy(public_path() . config($request->input('routesConfigFile') . '.libraryFolder') . '/' . $file['name'], public_path() . config($request->input('routesConfigFile') . '.tmpFolder') . '/' . $tmpFileName);

            $width = null; $height= null;
            if($file['isImage'] == 'true')
            {
                list($width, $height) = getimagesize(public_path() . config($request->input('routesConfigFile') . '.libraryFolder') . '/' . $file['name']);
            }

            $type = ImageManagerLibrary::getMimeIconImage($file['mime']);

            $objects[] = [
                'resource_014'      => $request->input('resource'),
                'url_014'           => null,
                'file_name_014'     => $file['name'],
                'mime_014'          => $file['mime'],
                'size_014'          => $file['size'],
                'type_014'          => $type['id'],
                'type_text_014'     => $type['name'],
                'width_014'         => $width,
                'height_014'        => $height,
                'data_014'          => json_encode(['icon' => $type['icon']])
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

        AttachmentLibrary::insert($objects);

        $lastLibraryInsert = AttachmentLibrary::whereIn('file_name_014', $filesNames)->get();

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


    public function deleteCustomRecord($request, $object)
    {
        $package = $object->getResource->getPackage;
        File::delete(public_path() . config($package->folder_012 . '.libraryFolder') . '/' . $object->file_name_014);
    }


    public function deleteCustomRecordsSelect($request, $ids)
    {
        $files = AttachmentLibrary::join('001_007_resource', '001_014_attachment_library.resource_014', '=', '001_007_resource.id_007')
            ->join('001_012_package', '001_007_resource.package_007', '=', '001_012_package.id_012')
            ->whereIn('id_014', $ids)
            ->get();

        $fileNames  = [];

        foreach($files as $file)
        {
            $fileNames[] = public_path() . config($file->folder_012 . '.libraryFolder') . '/' . $file->file_name_014;
        }

        File::delete($fileNames);
    }
}