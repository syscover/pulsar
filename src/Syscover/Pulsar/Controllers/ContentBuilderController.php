<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class ContentBuilderController extends Controller {

    public function index(Request $request)
    {
        // get parameters from url route, input y theme
        $parameters = $request->route()->parameters();

        if(file_exists (public_path() .config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/settings.json"))
        {
            $parameters['settings'] = json_decode(file_get_contents(public_path() .config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/settings.json"), true);
        }

        if(file_exists (public_path() .config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/content.stpl") && file_exists (public_path() .config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/settings.json"))
        {
            $css                = file_get_contents(public_path() . config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/content.stpl");
            $parameters['css']  = $this->changeWildcards($css, $parameters['settings']);
        }

        return view('pulsar::contentbuilder.index', $parameters);
    }

    public function saveImage(Request $request)
    {
        /*
        header('Cache-Control: no-cache, must-revalidate');

        //Specify storage folder
        $dir        = public_path() . '/packages/pulsar/comunik/storage/assets/';

        //Specify url path
        $path       = asset('/packages/pulsar/comunik/storage/assets/');

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

        return view('pulsar::pulsar.pulsar.common.html_display', $data);
        */
    }

    public function getBlocks(Request $request)
    {
        $parameters = $request->route()->parameters();
        //if(file_exists (public_path() .config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/settings.json"))
        //{


        $header = file_get_contents(public_path() . $request->input('themeFolder') . $parameters['theme'] . "/header.html");
        $footer = file_get_contents(public_path() . $request->input('themeFolder') . $parameters['theme'] . "/footer.html");

        //dd($request->all());
        //$header = $this->changeWildcards($header, $request->all());
/*
        $header = str_replace('#width#',            $request->input('width'),                $header);
        $header = str_replace('#backgroundColor#',  $request->input('backgroundColor'),      $header);
        $header = str_replace('#canvasColor#',      $request->input('canvasColor'),          $header);
        $header = str_replace('#highlightColor#',   $request->input('highlightColor'),       $header);
        $header = str_replace('#textColor#',        $request->input('textColor'),            $header);
        $header = str_replace('#titleColor#',       $request->input('titleColor'),           $header);
        $header = str_replace('#linkColor#',        $request->input('linkColor'),            $header);
*/
        return response()->json([
            'status'    => 'success',
            'header'    => $header,
            'footer'    => $footer
        ]);
    }

    private function changeWildcards($data, $args)
    {
        $data = str_replace('#width#',            $args['width'],                $data);
        $data = str_replace('#backgroundColor#',  $args['backgroundColor'],      $data);
        $data = str_replace('#canvasColor#',      $args['canvasColor'],          $data);
        $data = str_replace('#highlightColor#',   $args['highlightColor'],       $data);
        $data = str_replace('#textColor#',        $args['textColor'],            $data);
        $data = str_replace('#titleColor#',       $args['titleColor'],           $data);
        $data = str_replace('#linkColor#',        $args['linkColor'],            $data);

        return $data;
    }
}