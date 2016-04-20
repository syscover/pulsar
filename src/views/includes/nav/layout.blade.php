<ul id="nav">
    @foreach(session('packages') as $package)
        @if($package->folder_012 == 'pulsar')
            @if(is_allowed('admin', 'access') && View::exists('pulsar::includes.nav.main'))
                @include('pulsar::includes.nav.main')
            @endif
        @else
            @if(is_allowed($package->folder_012, 'access') && View::exists($package->folder_012 . '::includes.nav.main'))
                @include($package->folder_012 . '::includes.nav.main')
            @endif
        @endif
    @endforeach
</ul>