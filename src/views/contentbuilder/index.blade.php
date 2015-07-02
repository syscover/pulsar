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
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js') }}"></script>
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
        <div class="froala-editor f-basic" style="z-index: 2000;">
            <div class="bttn-wrapper" id="bttn-wrapper-1">
                <button tabindex="-1" type="button" class="fr-bttn" title="Bold" data-cmd="bold">
                    <i class="fa fa-bold"></i>
                </button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Italic" data-cmd="italic"><i
                            class="fa fa-italic"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Underline" data-cmd="underline"><i
                            class="fa fa-underline"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Strikethrough" data-cmd="strikeThrough"><i
                            class="fa fa-strikethrough"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Subscript" data-cmd="subscript"><i
                            class="fa fa-subscript"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Superscript" data-cmd="superscript"><i
                            class="fa fa-superscript"></i></button>
                <div class="fr-bttn fr-dropdown ">
                    <button tabindex="-1" type="button" data-name="fontSize" class="fr-trigger" title="Font Size"><i
                                class="fa fa-text-height"></i></button>
                    <ul class="fr-dropdown-menu f-font-sizes">
                        <li data-cmd="fontSize" data-val="11px"><a href="#"><span>11px</span></a></li>
                        <li data-cmd="fontSize" data-val="12px"><a href="#"><span>12px</span></a></li>
                        <li data-cmd="fontSize" data-val="13px" class="active"><a href="#"><span>13px</span></a></li>
                        <li data-cmd="fontSize" data-val="14px"><a href="#"><span>14px</span></a></li>
                    </ul>
                </div>
                <button tabindex="-1" type="button" class="fr-bttn active" title="Numbered List"
                        data-cmd="insertOrderedList"><i class="fa fa-list-ol"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Bulleted List" data-cmd="insertUnorderedList"><i
                            class="fa fa-list-ul"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Indent Less" data-cmd="outdent"><i
                            class="fa fa-dedent"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Indent More" data-cmd="indent"><i
                            class="fa fa-indent"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Select All" data-cmd="selectAll"><i
                            class="fa fa-file-text"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Insert Link" data-cmd="createLink"><i
                            class="fa fa-link"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Insert Image" data-cmd="insertImage"><i
                            class="fa fa-picture-o"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Insert Video" data-cmd="insertVideo"><i
                            class="fa fa-video-camera"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Undo" data-cmd="undo"><i class="fa fa-undo"></i>
                </button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Redo" data-cmd="redo" disabled=""><i
                            class="fa fa-repeat"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Show HTML" data-cmd="html"><i
                            class="fa fa-code"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Insert Horizontal Line"
                        data-cmd="insertHorizontalRule"><i class="fa fa-minus"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Upload File" data-cmd="uploadFile"><i class="fa fa-paperclip"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Clear formatting" data-cmd="removeFormat"><i class="fa fa-floppy-o"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Fullscreen" data-cmd="fullscreen"><i class="fa fa-expand"></i></button>
            </div>
        </div>
    </div>
    <div id="contentarea" class="drop-zone"></div>

    <!--
<ul class="sky-mega-menu sky-mega-menu-response-to-icons">

	<li>
		<a href="#"><i class="fa fa-tachometer"></i></a>
	</li>

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