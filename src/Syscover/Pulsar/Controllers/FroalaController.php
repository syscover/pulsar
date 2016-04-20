<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;

/**
 * Class FroalaController
 * @package Syscover\Pulsar\Controllers
 */

class FroalaController extends Controller
{
    public function uploadImage()
    {
        // Allowed extentions.
        $allowedExts = ["gif", "jpeg", "jpg", "png", "blob"];

        // Get filename.
        $temp = explode(".", $_FILES["file"]["name"]);

        // Get extension.
        $extension = end($temp);

        // An image check is being done in the editor but it is best to
        // check that again on the server side.
        // Do not use $_FILES["file"]["type"] as it can be easily forged.
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES["file"]["tmp_name"]);

        if ((($mime == "image/gif") || ($mime == "image/jpeg") || ($mime == "image/pjpeg") || ($mime == "image/x-png") || ($mime == "image/png")) && in_array(strtolower($extension), $allowedExts))
        {
            // set filename
            $extension  = $this->request->file('file')->getClientOriginalExtension();
            $basename   = str_slug(basename($this->request->file('file')->getClientOriginalName(), '.' . $extension));
            $filename   = $basename . '.' . $extension;

            $i = 0;
            while (file_exists(public_path() . '/packages/syscover/' . $this->request->input('package') . '/storage/wysiwyg/' . $filename))
            {
                $i++;
                $filename = $basename . '-' . $i . '.' . $extension;
            }

            // Save file in the uploads folder. file is the name of input file
            $this->request->file('file')->move(public_path() . '/packages/syscover/' . $this->request->input('package') . '/storage/wysiwyg', $filename);

            // Generate response.
            $response = new \StdClass;
            $response->link = '/packages/syscover/' . $this->request->input('package') . '/storage/wysiwyg/' . $filename;

            echo stripslashes(json_encode($response));
        }
    }

    public function loadImages()
    {
        // get parameters from url route
        $parameters = $this->request->route()->parameters();

        // Array of image objects to return.
        $response = [];

        // Image types.
        $image_types = [
            "image/gif",
            "image/jpeg",
            "image/pjpeg",
            "image/jpeg",
            "image/pjpeg",
            "image/png",
            "image/x-png"
        ];

        // Filenames in the uploads folder.
        $fnames = scandir(public_path() . '/packages/syscover/' . $parameters['package'] . '/storage/wysiwyg');

        // Check if folder exists.
        if ($fnames) {
            // Go through all the filenames in the folder.
            foreach ($fnames as $name) {
                // Filename must not be a folder.
                if (!is_dir($name)) {
                    // Check if file is an image.
                    if (in_array(mime_content_type(public_path() . '/packages/syscover/' . $parameters['package'] . '/storage/wysiwyg/' . $name), $image_types)) {
                        // Build the image.
                        $img = new \StdClass;
                        $img->url = '/packages/syscover/' . $parameters['package'] . '/storage/wysiwyg/' . $name;
                        $img->thumb = '/packages/syscover/' . $parameters['package'] . '/storage/wysiwyg/' . $name;
                        $img->name = $name;

                        // Add to the array of image.
                        array_push($response, $img);
                    }
                }
            }
        }

        // Folder does not exist, respond with a JSON to throw error.
        else {
            $response = new \StdClass;
            $response->error = "Images folder does not exist!";
        }

        $response = json_encode($response);

        // Send response.
        echo stripslashes($response);
    }

    public function deleteImage()
    {
        // Check if file exists.
        if (file_exists(public_path() . $this->request->input('src'))) {
            // Delete file.
            unlink(public_path() . $this->request->input('src'));
        }
    }

    public function uploadFile()
    {
        // Allowed extentions.
        $allowedExts = ["txt", "pdf", "doc"];

        // Get filename.
        $temp = explode(".", $_FILES["file"]["name"]);

        // Get extension.
        $extension = end($temp);

        // Validate uploaded files.
        // Do not use $_FILES["file"]["type"] as it can be easily forged.
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES["file"]["tmp_name"]);

        if ((($mime == "text/plain") || ($mime == "application/msword") || ($mime == "application/x-pdf") || ($mime == "application/pdf") && in_array(strtolower($extension), $allowedExts)))
        {
            // set filename
            $extension  = $this->request->file('file')->getClientOriginalExtension();
            $basename   = str_slug(basename($this->request->file('file')->getClientOriginalName(), '.' . $extension));
            $filename   = $basename . '.' . $extension;

            $i = 0;
            while (file_exists(public_path() . '/packages/syscover/' . $this->request->input('package') . '/storage/wysiwyg/' . $filename))
            {
                $i++;
                $filename = $basename . '-' . $i . '.' . $extension;
            }

            // Save file in the uploads folder. file is the name of input file
            $this->request->file('file')->move(public_path() . '/packages/syscover/' . $this->request->input('package') . '/storage/wysiwyg', $filename);

            // Generate response.
            $response = new \StdClass;
            $response->link = '/packages/syscover/' . $this->request->input('package') . '/storage/wysiwyg/' . $filename;

            echo stripslashes(json_encode($response));
        }
    }
}