@extends('pulsar::layouts.record')

@section('content')
    <!-- pulsar::layouts.form -->
    @include('pulsar::includes.html.form_record_header')
    @yield('rows')
    @include('pulsar::includes.html.form_record_footer')
    <!-- /pulsar::layouts.form -->
@stop