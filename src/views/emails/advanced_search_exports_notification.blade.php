@extends('pulsar::layouts.email')

@section('mainContent')
<table cellspacing="0" cellpadding="0" width="100%" style="background:#ffffff;margin:0;padding:0;border:0;text-align:left;border-collapse:collapse;border-spacing:0">
    <tr>
        <td colspan="2" width="100%">&nbsp;</td>
    </tr>
    <!-- advanced search -->
    <tr>
        <td colspan="2" class="header_body brown" valign="middle" width="100%" style='background:#ffffff;text-align:left;font-size:15px;line-height:19px;font-family:"Helvetica Neue",helvetica,arial,sans-serif;border-collapse:collapse;padding:0;border-spacing:0;vertical-align:middle;padding-left:10px;width:auto !important'>
            <strong style="color:brown;">{!! trans('pulsar::pulsar.message_advanced_search_exports_01', ['file' => $advancedSearch->filename_022 . '.' . $advancedSearch->extension_file_022]) !!}</strong>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="content_body" style='background:#ffffff;text-align:left;vertical-align:top;font-size:15px;line-height:19px;border-collapse:collapse;color:#000000;border-spacing:0;font-family:"Helvetica Neue",helvetica,arial,sans-serif;padding:0 0 0 55px'>
            <div class="formatted_content" style="padding-bottom:19px;padding:0 !important;border:none !important;margin:0 0 5px !important;max-width:none !important">
                {!! trans('pulsar::pulsar.message_advanced_search_exports_02', ['url' => route('downloadAdvancedSearch', ['token' => $token])]) !!}
            </div>
            <br>
        </td>
    </tr>
    <!-- /advanced search -->
    <tr>
        <td colspan="2" width="100%">&nbsp;</td>
    </tr>
</table>
@stop
