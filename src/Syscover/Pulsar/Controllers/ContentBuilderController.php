<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Http\Request;
use Leafo\ScssPhp\Compiler;

class ContentBuilderController extends Controller {

    public function index(Request $request)
    {
        // get parameters from url route, input y theme
        $parameters = $request->route()->parameters();


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

    public function saveImage(Request $request)
    {
        header('Cache-Control: no-cache, must-revalidate');

        //Specify storage folder
        $dir        = public_path() . '/packages/syscover/comunik/email/assets/';

        //Specify url path
        $path       = asset('/packages/syscover/comunik/email/assets/');

        //Read image
        $count      = $request->input('count');
        $b64str     = $request->input('hidimg-' . $count);
        $imgname    = $request->input('hidname-' . $count);
        $imgtype    = $request->input('hidtype-' . $count);

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

    public function getBlocks(Request $request)
    {
        $parameters = $request->route()->parameters();

        $scssSource = file_get_contents(public_path() . $request->input('themeFolder') . $parameters['theme'] . "/style.scss");

        // complile scss
        $scss = new Compiler();
        $scss->setVariables($request->input('settings'));
        $css = $scss->compile($scssSource);

        $header = file_get_contents(public_path() . $request->input('themeFolder') . $parameters['theme'] . "/header.html");
        $footer = file_get_contents(public_path() . $request->input('themeFolder') . $parameters['theme'] . "/footer.html");

        // include css in header
        $header = str_replace('/* --INCLUDE CSS-- */', $css, $header);

        return response()->json([
            'status'    => 'success',
            'header'    => $header,
            'footer'    => $footer
        ]);
    }
}