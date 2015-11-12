<li{!! Miscellaneous::setCurrentOpenPage(['admin-user', 'admin-lang', 'admin-country', 'admin-country-at1', 'admin-country-at2', 'admin-country-at3', 'admin-package', 'admin-cron', 'admin-perm-profile', 'admin-perm-resource', 'admin-perm-action', 'admin-perm-perm', 'admin-email-account', 'admin-attachment-family', 'admin-attachment-library','admin-field-family']) !!}>
    <a href="javascript:void(0)"><i class="fa fa-cog"></i>{{ trans('pulsar::pulsar.administration') }}</a>
    <ul class="sub-menu">
        @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-user', 'access'))
            <li{!! Miscellaneous::setCurrentPage('admin-user') !!}><a href="{{ route('User') }}"><i class="icomoon-icon-users"></i>{{ trans_choice('pulsar::pulsar.user', 2) }}</a></li>
        @endif
        @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-lang', 'access'))
                <li{!! Miscellaneous::setCurrentPage('admin-lang') !!}><a href="{{ route('Lang') }}"><i class="fa fa-language"></i>{{ trans_choice('pulsar::pulsar.language', 2) }}</a></li>
        @endif
        @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-country', 'access'))
                <li{!! Miscellaneous::setCurrentPage(array('admin-country','admin-country-at1','admin-country-at2','admin-country-at3')) !!}><a href="{{ route('Country', [session('baseLang')]) }}"><i class="fa fa-globe"></i>{{ trans_choice('pulsar::pulsar.country', 2) }}</a></li>
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
        @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-field', 'access'))
            <li{!! Miscellaneous::setOpenPage(['admin-field-family']) !!}>
                <a href="javascript:void(0)"><i class="fa fa-cubes"></i>{{ trans_choice('pulsar::pulsar.custom_field', 2) }}<span class="arrow"></span></a>
                <ul class="sub-menu"{!! Miscellaneous::setDisplayPage(['admin-field-family']) !!}>
                    @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-attachment-family', 'access'))
                        <li{!! Miscellaneous::setCurrentPage('admin-field-family') !!}><a href="{{ route('CustomFieldFamily') }}"><i class="fa fa-th"></i>{{ trans_choice('pulsar::pulsar.field_family', 2) }}</a></li>
                    @endif
                </ul>
            </li>
        @endif
        @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-attachment', 'access'))
            <li{!! Miscellaneous::setOpenPage(['admin-attachment-family', 'admin-attachment-library']) !!}>
                <a href="javascript:void(0)"><i class="fa fa-paperclip"></i>{{ trans_choice('pulsar::pulsar.attachment', 2) }}<span class="arrow"></span></a>
                <ul class="sub-menu"{!! Miscellaneous::setDisplayPage(['admin-attachment-family', 'admin-attachment-library']) !!}>
                    @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-attachment-family', 'access'))
                        <li{!! Miscellaneous::setCurrentPage('admin-attachment-family') !!}><a href="{{ route('AttachmentFamily') }}"><i class="fa fa-th"></i>{{ trans_choice('pulsar::pulsar.attachment_family', 2) }}</a></li>
                    @endif
                    @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-attachment-library', 'access'))
                        <li{!! Miscellaneous::setCurrentPage('admin-attachment-library') !!}><a href="{{ route('AttachmentLibrary') }}"><i class="fa fa-book"></i>{{ trans_choice('pulsar::pulsar.library', 2) }}</a></li>
                    @endif
                </ul>
            </li>
        @endif
        @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm', 'access'))
            <li{!! Miscellaneous::setOpenPage(['admin-perm-profile', 'admin-perm-resource', 'admin-perm-action', 'admin-perm-profile', 'admin-perm-perm']) !!}>
                <a href="javascript:void(0)"><i class="fa fa-shield"></i>{{ trans_choice('pulsar::pulsar.permission', 2) }}<span class="arrow"></span></a>
                <ul class="sub-menu"{!! Miscellaneous::setDisplayPage(['admin-perm-resource', 'admin-perm-action', 'admin-perm-profile', 'admin-perm-perm']) !!}>
                    @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm-profile', 'access'))
                        <li{!! Miscellaneous::setCurrentPage(array('admin-perm-profile', 'admin-perm-perm')) !!}><a href="{{ route('Profile') }}"><i class="icomoon-icon-users-2"></i>{{ trans_choice('pulsar::pulsar.profile', 2) }}</a></li>
                    @endif
                    @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm-resource', 'access'))
                        <li{!! Miscellaneous::setCurrentPage('admin-perm-resource') !!}><a href="{{ route('Resource') }}"><i class="icomoon-icon-database"></i>{{ trans_choice('pulsar::pulsar.resource', 2) }}</a></li>
                    @endif
                    @if(session('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm-action', 'access'))
                        <li{!! Miscellaneous::setCurrentPage('admin-perm-action') !!}><a href="{{ route('Action') }}"><i class="fa fa-bolt"></i>{{ trans_choice('pulsar::pulsar.action', 2) }}</a></li>
                    @endif
                </ul>
            </li>
        @endif
    </ul>
</li>
