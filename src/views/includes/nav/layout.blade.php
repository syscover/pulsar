<ul id="nav">
    @foreach(session('packages') as $package)
        @if(session('userAcl')->allows($package->folder_012, 'access') && View::exists($package->folder_012 . '::includes.nav.main'))
            @include($package->folder_012 . '::includes.nav.main')
        @endif
    @endforeach
</ul>