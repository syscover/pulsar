<ul id="nav">
    @foreach(session('packages') as $package)
        @if(session('userAcl')->isAllowed(Auth::user()->profile_010, $package->folder_012, 'access') && View::exists($package->folder_012 . '::includes.nav.main'))
            @include($package->folder_012 . '::includes.nav.main')
        @endif
    @endforeach
</ul>