<ul id="nav">
    @foreach(session('packages') as $package)
        @if($package->folder_012 == 'pulsar')
            @if(session('userAcl')->allows('admin', 'access') && View::exists('pulsar::includes.nav.main'))
                @include('pulsar::includes.nav.main')
            @endif
        @else
            @if(session('userAcl')->allows($package->folder_012, 'access') && View::exists($package->folder_012 . '::includes.nav.main'))
                @include($package->folder_012 . '::includes.nav.main')
            @endif
        @endif
    @endforeach
</ul>