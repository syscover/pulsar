@extends('pulsar::layouts.record')

@section('content')
    <!-- pulsar::layouts.tab -->
    <div class="tabbable box-tabs">
        <ul class="nav nav-tabs">
            @foreach(array_reverse($tabs) as $tab)
            <li><a href="#{{ $tab['id'] }}" data-toggle="tab">{{ $tab['name'] }}</a></li>
            @endforeach
        </ul>
        <div class="tab-content">
        @foreach($tabs as $tab)
            <div class="tab-pane" id="{{ $tab['id'] }}">
                @yield($tab['id'])
            </div>
        @endforeach
        </div>
    </div>
    <!-- /pulsar::layouts.tab -->
@stop