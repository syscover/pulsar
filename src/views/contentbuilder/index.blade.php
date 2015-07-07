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
        function getContentBuilderHtml()
        {
            return $('#contentarea').data('contentbuilder').html();
        }

        function getParentHtml(name)
        {
            $('#contentarea').data('contentbuilder').loadHTML(parent.$('[name=' + name + ']').val());
        }
    </script>
@stop

@section('mainContent')
    <div class="froala-box">
        <div class="froala-editor f-basic f-scroll">
            <div class="bttn-wrapper" id="bttn-wrapper-1">
                <button tabindex="-1" type="button" class="fr-bttn" title="Bold" data-cmd="bold"><i class="fa fa-tachometer"></i></button>
                <button tabindex="-1" type="button" class="fr-bttn" title="Fullscreen" data-cmd="fullscreen"><i class="fa fa-expand"></i></button>
            </div>
        </div>
    </div>
    <div id="contentarea" class="drop-zone"></div>
@stop