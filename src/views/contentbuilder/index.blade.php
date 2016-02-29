@extends('pulsar::layouts.blank')

@section('head')
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/wysiwyg.froala/css/froala_editor.min.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/wysiwyg.froala/css/froala_style.min.css') }}">

    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/contentbuilder/css/contentbuilder.css?07') }}">
    <link rel="stylesheet" href="{{ asset('packages/syscover/pulsar/vendor/contentbuilder/scripts/contentbuilder.css?07') }}">
    <script src="{{ asset('packages/syscover/pulsar/js/libs/jquery-2.1.3.min.js?05') }}"></script>
    <script src="{{ asset('packages/syscover/pulsar/vendor/jquery-ui/jquery-ui.min.js?05') }}"></script>
    <script src="{{ asset('packages/syscover/pulsar/vendor/contentbuilder/scripts/contentbuilder.js?07') }}"></script>
    <script src="{{ asset('packages/syscover/pulsar/vendor/contentbuilder/scripts/jquery.saveimages.js?07') }}"></script>

    <!-- theme css -->
    @if(file_exists(public_path() . config($package . '.themesFolder') . $theme . '/content.css'))
        <link rel="stylesheet" href="{{ asset(config($package . '.themesFolder') . $theme . '/content.css') }}">
    @endif
    <style type="text/css">
        {{ $css or null }}
    </style>
    <!-- ./theme css -->

    <script type="text/javascript">
        $(document).ready(function($){

            // create contentBuilder object
            $("#contentarea").contentbuilder({
                zoom: 1,
                snippetFile:        '{{ asset(config($package . '.themesFolder') . $theme . '/snippets.html?08') }}',
                snippetPathReplace: ['{{ config($package . '.themesFolder') . $theme }}','{{ asset(config($package . '.themesFolder') . $theme . '/') }}'],
                snippetTool:        'left'
            });

            @if(isset($input))
            // load html from parent if input cont
            $("#contentarea").data('contentbuilder').loadHTML(parent.$('[name={{ $input }}]').val());
            @endif

            // fullscreen function
            $('.fr-toolbar .fa-expand').parent('a').on('click', function(){
                if($(this).hasClass('active'))
                {
                    $(this).removeClass('active');
                    parent.$('.iframe-contentbuilder').removeClass('fr-fullscreen');
                    parent.$('body').removeClass('lock-scroll');
                }
                else
                {
                    $(this).addClass('active');
                    parent.$('.iframe-contentbuilder').addClass('fr-fullscreen');
                    parent.$('body').addClass('lock-scroll');
                }
            });

            // set canvas width
            $('[name=canvasWidth]').val({{ $settings['width'] }});
            $('#contentarea').css("width", "{{ $settings['width'] }}px");
            $('[name=canvasWidth]').on('change', function(e) {
                $('#contentarea').css("width", e.target.value+"px");
            });

            // save function
            $('.fr-toolbar .fa-floppy-o').parent('a').on('click', function(){

                parent.$.cssLoader.show({useLayer: false, layerOpacity: 100});

                // declare save function
                $("#contentarea").saveimages({
                    handler: '{{ route('contentbuilderSaveImage') }}',
                    csfr: '{{ csrf_token() }}',
                    onComplete: function () {
                        var html                   = $('#contentarea').data('contentbuilder').html();
                        var settings  = JSON.parse('{!! json_encode($settings) !!}');

                        /*
                         settings.width              = $('#canvasWidth').val();
                         settings.backgroundColor    = $('#backgroundColor').attr('data-value');
                         settings.canvasColor        = $('#canvasColor').attr('data-value');
                         settings.highlightColor     = $('#highlightColor').attr('data-value');
                         settings.textColor          = $('#textColor').attr('data-value');
                         settings.titleColor         = $('#titleColor').attr('data-value');
                         settings.linkColor          = $('#linkColor').attr('data-value');
                         */

                        if (typeof parent.getValueContentBuilder == 'function')
                            parent.getValueContentBuilder(html, settings);

                        parent.$.cssLoader.hide();
                        if(parent.$.fn.magnificPopup && parent.$.magnificPopup.instance.isOpen)
                            parent.$.magnificPopup.instance.close();
                    }
                });

                // execute save function
                $("#contentarea").data('saveimages').save();
            });
        });
        var getContentBuilderHtml = function(){
            return $('#contentarea').data('contentbuilder').html();
        }

        var getParentHtml = function(name)
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
                <!--<a role="button" tabindex="-1" class="fr-command fr-btn fr-visible-sm fr-visible-md" data-cmd=""><i class="fa fa-tachometer"></i></a>-->
                @if(isset($settings['fullscreenButton']) && $settings['fullscreenButton'])<a role="button" tabindex="-1" class="fr-command fr-btn fr-visible-sm fr-visible-md"><i class="fa fa-expand"></i></a>@endif
                @if(isset($settings['saveButton']) && $settings['saveButton'])<a role="button" tabindex="-1" class="fr-command fr-btn fr-visible-sm fr-visible-md"><i class="fa fa-floppy-o"></i></a>@endif
                @if(isset($settings['setWidth']) && $settings['setWidth'])<span>Ancho: <input name="canvasWidth" size="5" style="padding: 3px; border-radius: 2px; border: 1px solid silver" value=""> px</span>@endif
            </div>
        </div>
    </div>
    <div id="contentarea" class="drop-zone"></div>
@stop