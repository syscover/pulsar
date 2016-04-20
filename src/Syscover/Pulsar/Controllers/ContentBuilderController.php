<?php namespace Syscover\Pulsar\Controllers;

use Syscover\Pulsar\Core\Controller;
use Leafo\ScssPhp\Compiler;

/**
 * Class ContentBuilderController
 * @package Syscover\Pulsar\Controllers
 */

class ContentBuilderController extends Controller
{
    public function index()
    {
        // get parameters from url route, input y theme
        $parameters = $this->request->route()->parameters();


        if(file_exists (public_path() .config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/settings.json"))
        {
            // get settings variables predefined
            $parameters['settings'] = json_decode(file_get_contents(public_path() .config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/settings.json"), true);

            // get scss file
            if(file_exists (public_path() .config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/style.scss") && file_exists (public_path() .config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/settings.json"))
            {
                $scssSource = file_get_contents(public_path() . config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/style.scss");

                // complile scss
                $scss = new Compiler();
                $scss->setVariables($parameters['settings']);
                $parameters['css'] = $scss->compile($scssSource);
            }
        }

        return view('pulsar::contentbuilder.index', $parameters);
    }

    public function saveImage()
    {
        header('Cache-Control: no-cache, must-revalidate');

        //Specify storage folder
        $dir        = public_path() . '/packages/syscover/comunik/email/assets/';

        //Specify url path
        $path       = asset('/packages/syscover/comunik/email/assets/');

        //Read image
        $count      = $this->request->input('count');
        $b64str     = $this->request->input('hidimg-' . $count);
        $imgname    = $this->request->input('hidname-' . $count);
        $imgtype    = $this->request->input('hidtype-' . $count);

        //Generate random file name here
        if($imgtype == 'png')
        {
            $image = $imgname . '-' . base_convert(rand(),10,36) . '.png';
        }
        else
        {
            $image = $imgname . '-' . base_convert(rand(),10,36) . '.jpg';
        }

        //Save image
        file_put_contents($dir . $image, base64_decode($b64str));

        $data['html'] = "<html><body onload=\"parent.document.getElementById('img-" . $count . "').setAttribute('src','" . $path. '/' . $image . "');  parent.document.getElementById('img-" . $count . "').removeAttribute('id') \"></body></html>";

        return view('pulsar::common.views.html_display', $data);
    }

    public function getBlocks()
    {
        $parameters = $this->request->route()->parameters();

        $scssSource = file_get_contents(public_path() . $this->request->input('themeFolder') . $parameters['theme'] . "/style.scss");

        // complile scss
        $scss = new Compiler();
        $scss->setVariables($this->request->input('settings'));
        $css = $scss->compile($scssSource);

        $header = file_get_contents(public_path() . $this->request->input('themeFolder') . $parameters['theme'] . "/header.html");
        $footer = file_get_contents(public_path() . $this->request->input('themeFolder') . $parameters['theme'] . "/footer.html");

        // include css in header
        $header = str_replace('/* --INCLUDE CSS-- */', $css, $header);

        return response()->json([
            'status'    => 'success',
            'header'    => $header,
            'footer'    => $footer
        ]);
    }
}