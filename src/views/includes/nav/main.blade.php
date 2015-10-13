<ul id="nav">
    @if(isset(session('packages')[9]) && session('packages')[9]->active_012 && session('userAcl')->isAllowed(Auth::user()->profile_010, 'market', 'access') && View::exists('marketplace::includes.nav.main'))
        @include('marketplace::includes.nav.main')
    @endif

    @if(isset(session('packages')[12]) &&session('packages')[12]->active_012 && session('userAcl')->isAllowed(Auth::user()->profile_010, 'crm', 'access') && View::exists('crm::includes.nav.main'))
        @include('crm::includes.nav.main')
    @endif

    @if(isset(session('packages')[10]) && session('packages')[10]->active_012 && session('userAcl')->isAllowed(Auth::user()->profile_010, 'mifinan', 'access') && View::exists('mifinanciacion::includes.nav.main'))
        @include('mifinanciacion::includes.nav.main')
    @endif

    @if(isset(session('packages')[8]) && session('packages')[8]->active_012 && session('userAcl')->isAllowed(Auth::user()->profile_010, 'octopus', 'access') && View::exists('octopus::includes.nav.main'))
        @include('octopus::includes.nav.main')
    @endif

    @if(isset(session('packages')[7]) && session('packages')[7]->active_012 && session('userAcl')->isAllowed(Auth::user()->profile_010, 'hotels', 'access') && View::exists('hotels::includes.nav.main'))
        @include('hotels::includes.nav.main')
    @endif

    @if(isset(session('packages')[11]) && session('packages')[11]->active_012 && session('userAcl')->isAllowed(Auth::user()->profile_010, 'booking', 'access') && View::exists('booking::includes.nav.main'))
        @include('booking::includes.nav.main')
    @endif

    @if(isset(session('packages')[13]) && session('packages')[13]->active_012 && session('userAcl')->isAllowed(Auth::user()->profile_010, 'cms', 'access') && View::exists('cms::includes.nav.main'))
        @include('cms::includes.nav.main')
    @endif

    @if(isset(session('packages')[4]) && session('packages')[4]->active_012 && session('userAcl')->isAllowed(Auth::user()->profile_010, 'forms', 'access') && View::exists('forms::includes.nav.main'))
        @include('forms::includes.nav.main')
    @endif

    @if(isset(session('packages')[3]) && session('packages')[3]->active_012 && session('userAcl')->isAllowed(Auth::user()->profile_010, 'comunik', 'access') && View::exists('comunik::includes.nav.main'))
        @include('comunik::includes.nav.main')
    @endif

    @if(isset(session('packages')[2]) && session('packages')[2]->active_012 && session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin', 'access'))
        <li{!! Miscellaneous::setCurrentOpenPage(['admin-user', 'admin-lang', 'admin-country', 'admin-country-at1', 'admin-country-at2', 'admin-country-at3', 'admin-package', 'admin-cron', 'admin-perm-profile', 'admin-perm-resource', 'admin-perm-action', 'admin-perm-perm', 'admin-email-account', 'admin-attachment-family']) !!}>
            <a href="javascript:void(0);"><i class="fa fa-cog"></i>{{ trans('pulsar::pulsar.administration') }}</a>
            <ul class="sub-menu">
                @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-user', 'access'))
                    <li{!! Miscellaneous::setCurrentPage('admin-user') !!}><a href="{{ route('User') }}"><i class="icomoon-icon-users"></i>{{ trans_choice('pulsar::pulsar.user', 2) }}</a></li>
                @endif
                @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-lang', 'access'))
                        <li{!! Miscellaneous::setCurrentPage('admin-lang') !!}><a href="{{ route('Lang') }}"><i class="brocco-icon-flag"></i>{{ trans_choice('pulsar::pulsar.language', 2) }}</a></li>
                @endif
                @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-country', 'access'))
                        <li{!! Miscellaneous::setCurrentPage(array('admin-country','admin-country-at1','admin-country-at2','admin-country-at3')) !!}><a href="{{ route('Country', [session('baseLang')]) }}"><i class="entypo-icon-globe"></i>{{ trans_choice('pulsar::pulsar.country', 2) }}</a></li>
                @endif
                @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-package', 'access'))
                        <li{!! Miscellaneous::setCurrentPage('admin-package') !!}><a href="{{ route('Package') }}"><i class="cut-icon-grid"></i>{{ trans_choice('pulsar::pulsar.package', 2) }}</a></li>
                @endif
                @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-cron', 'access'))
                        <li{!! Miscellaneous::setCurrentPage('admin-cron') !!}><a href="{{ route('CronJob') }}"><i class="icomoon-icon-stopwatch"></i>{{ trans_choice('pulsar::pulsar.cronjob', 2) }}</a></li>
                @endif
                @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-email-account', 'access'))
                        <li{!! Miscellaneous::setCurrentPage('admin-email-account') !!}><a href="{{ route('EmailAccount') }}"><i class="fa fa-envelope"></i>{{ trans_choice('pulsar::pulsar.account', 2) }}</a></li>
                @endif
                @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-attachment', 'access'))
                    <li{!! Miscellaneous::setOpenPage(['admin-attachment-family']) !!}>
                        <a href="javascript:void(0);"><i class="fa fa-paperclip"></i>{{ trans_choice('pulsar::pulsar.attachment', 2) }}<span class="arrow"></span></a>
                        <ul class="sub-menu"{!! Miscellaneous::setDisplayPage(['admin-attachment-family']) !!}>
                            @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm-profile', 'access'))
                                <li{!! Miscellaneous::setCurrentPage(array('admin-perm-profile', 'admin-perm-perm')) !!}><a href="{{ route('Profile') }}"><i class="icomoon-icon-users-2"></i>{{ trans_choice('pulsar::pulsar.profile', 2) }}</a></li>
                            @endif
                            @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm-resource', 'access'))
                                <li{!! Miscellaneous::setCurrentPage('admin-perm-resource') !!}><a href="{{ route('Resource') }}"><i class="icomoon-icon-database"></i>{{ trans_choice('pulsar::pulsar.resource', 2) }}</a></li>
                            @endif
                            @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-attachment-family', 'access'))
                                <li{!! Miscellaneous::setCurrentPage('admin-attachment-family') !!}><a href="{{ route('AttachmentFamily') }}"><i class="fa fa-th"></i>{{ trans_choice('pulsar::pulsar.attachment_family', 2) }}</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm', 'access'))
                    <li{!! Miscellaneous::setOpenPage(['admin-perm-profile', 'admin-perm-resource', 'admin-perm-action', 'admin-perm-profile', 'admin-perm-perm']) !!}>
                        <a href="javascript:void(0);"><i class="fa fa-user"></i>{{ trans_choice('pulsar::pulsar.permission', 2) }}<span class="arrow"></span></a>
                        <ul class="sub-menu"{!! Miscellaneous::setDisplayPage(['admin-perm-resource', 'admin-perm-action', 'admin-perm-profile', 'admin-perm-perm']) !!}>
                            @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm-profile', 'access'))
                                <li{!! Miscellaneous::setCurrentPage(array('admin-perm-profile', 'admin-perm-perm')) !!}><a href="{{ route('Profile') }}"><i class="icomoon-icon-users-2"></i>{{ trans_choice('pulsar::pulsar.profile', 2) }}</a></li>
                            @endif
                            @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm-resource', 'access'))
                                <li{!! Miscellaneous::setCurrentPage('admin-perm-resource') !!}><a href="{{ route('Resource') }}"><i class="icomoon-icon-database"></i>{{ trans_choice('pulsar::pulsar.resource', 2) }}</a></li>
                            @endif
                            @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm-action', 'access'))
                                <li{!! Miscellaneous::setCurrentPage('admin-perm-action') !!}><a href="{{ route('Action') }}"><i class="icomoon-icon-power"></i>{{ trans_choice('pulsar::pulsar.action', 2) }}</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </li>
    @endif
</ul>