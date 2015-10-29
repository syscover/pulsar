<?php namespace Syscover\Pulsar\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class ContentBuilderController extends Controller {

    public function index(Request $request)
    {
        // get parameters from url route, input y theme
        $parameters        = $request->route()->parameters();

        if(file_exists (public_path() .config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/settings.json" ))
        {
            $css               = file_get_contents(public_path() . config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/content.stpl");
            $settings          = json_decode(file_get_contents(public_path() .config($parameters['package'] . '.themesFolder') . $parameters['theme'] . "/settings.json"), true);
            $parameters['css'] = $this->changeWildcards($css, $settings);
        }

        return view('pulsar::contentbuilder.index', $parameters);
    }

    public function saveImage()
    {
        header('Cache-Control: no-cache, must-revalidate');

        //Specify storage folder
        $dir        = public_path().'/packages/pulsar/comunik/storage/assets/';

        //Specify url path
        $path       = URL::asset('/packages/pulsar/comunik/storage/assets/');

        //Read image
        $count      = Input::get('count');
        $b64str     = Input::get('hidimg-'.$count);
        $imgname    = Input::get('hidname-'.$count);
        $imgtype    = Input::get('hidtype-'.$count);

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

        return View::make('pulsar::pulsar.pulsar.common.html_display',$data);
    }

    public function getBlocks($theme)
    {

        $header = public_path() . '/packages/syscover/pulsar/vendor/contentbuilder/themes/' . $theme . "/header.html";
        $footer = public_path() . '/packages/syscover/pulsar/vendor/contentbuilder/themes/' . $theme . "/footer.html";

        $data['header'] = file_get_contents($header);
        $data['footer'] = file_get_contents($footer);

        $data['header'] = str_replace('#width#',            Input::get('width'),                $data['header']);
        $data['header'] = str_replace('#backgroundColor#',  Input::get('backgroundColor'),      $data['header']);
        $data['header'] = str_replace('#canvasColor#',      Input::get('canvasColor'),          $data['header']);
        $data['header'] = str_replace('#highlightColor#',   Input::get('highlightColor'),       $data['header']);
        $data['header'] = str_replace('#textColor#',        Input::get('textColor'),            $data['header']);
        $data['header'] = str_replace('#titleColor#',       Input::get('titleColor'),           $data['header']);
        $data['header'] = str_replace('#linkColor#',        Input::get('linkColor'),            $data['header']);

        $data['json'] = json_encode($data);

        return View::make('pulsar::pulsar.pulsar.common.json_display',$data);
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