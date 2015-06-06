@extends('pulsar::layouts.record')

@section('content')
    <!-- pulsar::layouts.form -->
    @include('pulsar::includes.html.form_record_header')
    @yield('rows')
    @include('pulsar::includes.html.form_record_footer')

    @yield('outForm') <!-- popups forms inline -->
    <!-- /pulsar::layouts.form -->
@stop