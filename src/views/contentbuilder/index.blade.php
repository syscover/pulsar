@extends('pulsar::layouts.blank')

@section('css')
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/contentbuilder/scripts/contentbuilder.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/contentbuilder/css/contentbuilder.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/wysiwyg.froala/css/froala_editor.min.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/wysiwyg.froala/css/froala_style.min.css') }}">

    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/contentbuilder/themes/default/content.css') }}">

    <style type="text/css">
        {{ $css or null }}
    </style>
@stop

@section('script')
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/js/libs/jquery-2.1.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/contentbuilder/js/contentbuilder.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/contentbuilder/scripts/contentbuilder.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/contentbuilder/scripts/saveimages.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function($){

            $("#contentarea").contentbuilder({
                zoom: 1,
                snippetFile: '{{ asset('packages/syscover/pulsar/vendor/contentbuilder/themes/' . $theme . '/snippets.html') }}',
                snippetTool: 'left'
            });
        });
    </script>
    <style>
        body{
            margin: 0;

        }
        .fr-bttn i{
            font-size: 13px;
        }
        .froala-box {
            border-bottom: solid 1px #252525;
        }
        .froala-editor{
            border: none;
            border-top: solid 4px #252525;
        }

    </style>
@stop

@section('mainContent')
    <div class="froala-box">
        <div class="froala-editor f-basic f-scroll" style="z-index: 2000;">
            <div class="bttn-wrapper" id="bttn-wrapper-1">
                <button tabindex="-1" type="button" class="fr-bttn" title="Bold" data-cmd="bold"><i class="fa fa-tachometer"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Fullscreen" data-cmd="fullscreen"><i class="fa fa-expand"></i></button>
            </div>
        </div>
    </div>
    <div id="contentarea" class="drop-zone"></div>

    <!--
<ul class="sky-mega-menu sky-mega-menu-response-to-icons">



    <li>
        Ancho: <input id="canvasWidth" size="5" style="padding: 3px; border-radius: 2px; border: 1px solid silver" value=""> px
    </li>

	<li>
		<a href="#"><i class="fa fa-eyedropper"></i>Colores</a>
		<div class="grid-container3">
			<ul>
			    <li><a href="#">Background color <i id="backgroundColor" class="color-box" data-value=""></i></a></li>
			    <li><a href="#">Canvas color <i id="canvasColor" class="color-box" data-value=""></i></a></li>
			    <li><a href="#">Highlight color <i id="highlightColor" class="color-box" data-value=""></i></a></li>
			    <li><a href="#">Text color <i id="textColor" class="color-box" data-value=""></i></a></li>
			    <li><a href="#">Title color <i id="titleColor" class="color-box" data-value=""></i></a></li>
			    <li><a href="#">Link color <i id="linkColor" class="color-box" data-value=""></i></a></li>
			</ul>
		</div>
	</li>

    <li>
        <a href="#" id="wildcards"><i class="fa fa-cubes"></i>Comodines</a>
    </li>

	<li class="right">
		<a href="#" id="save"><i class="fa fa-floppy-o"></i>Guardar plantilla</a>
	</li>

</ul>
    -->

@stop