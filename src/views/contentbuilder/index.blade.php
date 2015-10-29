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
                snippetFile: '{{ asset(config($package . '.themesFolder') . $theme . '/snippets.html') }}',
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
    <!-- toolbar to contentbuider -->
    <div class="fr-box fr-top fr-basic">
        <div class="fr-toolbar fr-ltr fr-desktop fr-top fr-basic fr-sticky fr-sticky-off">
            <div class="bttn-wrapper" id="bttn-wrapper-1">
                <!-- <a role="button" tabindex="-1" class="fr-command fr-btn fr-visible-sm fr-visible-md" data-cmd=""><i class="fa fa-tachometer"></i></a> -->
                <a role="button" tabindex="-1" class="fr-command fr-btn fr-visible-sm fr-visible-md" data-cmd="fullscreen"><i class="fa fa-expand"></i></a>
            </div>
        </div>
    </div>
    <div id="contentarea" class="drop-zone"></div>
@stop