<li{!! is_current_resource(['admin-user', 'admin-lang', 'admin-country', 'admin-country-at1', 'admin-country-at2', 'admin-country-at3', 'admin-package', 'admin-cron', 'admin-perm-profile', 'admin-perm-resource', 'admin-perm-action', 'admin-perm-perm', 'admin-email-account', 'admin-attachment-family', 'admin-attachment-mime', 'admin-attachment-library','admin-field', 'admin-field-value', 'admin-field-group']) !!}>
    <a href="javascript:void(0)"><i class="fa fa-cog"></i>{{ trans('pulsar::pulsar.administration') }}</a>
    <ul class="sub-menu">
        @if(is_allowed('admin-user', 'access'))
            <li{!! is_current_resource('admin-user') !!}><a href="{{ route('user') }}"><i class="fa fa-users"></i>{{ trans_choice('pulsar::pulsar.user', 2) }}</a></li>
        @endif
        @if(is_allowed('admin-report', 'access'))
            <li{!! is_current_resource('admin-report') !!}><a href="{{ route('reportTask') }}"><i class="fa fa-area-chart"></i>{{ trans_choice('pulsar::pulsar.report', 2) }}</a></li>
        @endif
        @if(is_allowed('admin-lang', 'access'))
            <li{!! is_current_resource('admin-lang') !!}><a href="{{ route('lang') }}"><i class="fa fa-language"></i>{{ trans_choice('pulsar::pulsar.language', 2) }}</a></li>
        @endif
        @if(is_allowed('admin-country', 'access'))
            <li{!! is_current_resource(['admin-country','admin-country-at1','admin-country-at2','admin-country-at3']) !!}><a href="{{ route('country', [base_lang()->id_001]) }}"><i class="fa fa-globe"></i>{{ trans_choice('pulsar::pulsar.country', 2) }}</a></li>
        @endif
        @if(is_allowed('admin-package', 'access'))
            <li{!! is_current_resource('admin-package') !!}><a href="{{ route('package') }}"><i class="cut-icon-grid"></i>{{ trans_choice('pulsar::pulsar.package', 2) }}</a></li>
        @endif
        @if(is_allowed('admin-cron', 'access'))
            <li{!! is_current_resource('admin-cron') !!}><a href="{{ route('cronJob') }}"><i class="icomoon-icon-stopwatch"></i>{{ trans_choice('pulsar::pulsar.cronjob', 2) }}</a></li>
        @endif
        @if(is_allowed('admin-email-account', 'access'))
            <li{!! is_current_resource('admin-email-account') !!}><a href="{{ route('emailAccount') }}"><i class="fa fa-envelope"></i>{{ trans_choice('pulsar::pulsar.account', 2) }}</a></li>
        @endif
        @if(is_allowed('admin-field', 'access'))
            <li{!! is_current_resource(['admin-field', 'admin-field-value', 'admin-field-group'], true) !!}>
                <a href="javascript:void(0)"><i class="fa fa-cubes"></i>{{ trans_choice('pulsar::pulsar.custom_field', 2) }}<span class="arrow"></span></a>
                <ul class="sub-menu">
                    @if(is_allowed('admin-field', 'access'))
                        <li{!! is_current_resource(['admin-field', 'admin-field-value']) !!}><a href="{{ route('customField', [base_lang()->id_001]) }}"><i class="fa fa-i-cursor"></i>{{ trans_choice('pulsar::pulsar.field', 2) }}</a></li>
                    @endif
                    @if(is_allowed('admin-field-group', 'access'))
                        <li{!! is_current_resource('admin-field-group') !!}><a href="{{ route('customFieldGroup') }}"><i class="fa fa-th"></i>{{ trans_choice('pulsar::pulsar.field_group', 2) }}</a></li>
                    @endif
                </ul>
            </li>
        @endif
        @if(is_allowed('admin-attachment', 'access'))
            <li{!! is_current_resource(['admin-attachment-family', 'admin-attachment-mime', 'admin-attachment-library'], true) !!}>
                <a href="javascript:void(0)"><i class="fa fa-paperclip"></i>{{ trans_choice('pulsar::pulsar.attachment', 2) }}<span class="arrow"></span></a>
                <ul class="sub-menu">
                    @if(is_allowed('admin-attachment-family', 'access'))
                        <li{!! is_current_resource('admin-attachment-family') !!}><a href="{{ route('attachmentFamily') }}"><i class="fa fa-th"></i>{{ trans_choice('pulsar::pulsar.attachment_family', 2) }}</a></li>
                    @endif
                    @if(is_allowed('admin-attachment-mime', 'access'))
                        <li{!! is_current_resource('admin-attachment-mime') !!}><a href="{{ route('attachmentMime') }}"><i class="fa fa-file"></i>{{ trans_choice('pulsar::pulsar.attachment_mime', 2) }}</a></li>
                    @endif
                    @if(is_allowed('admin-attachment-library', 'access'))
                        <li{!! is_current_resource('admin-attachment-library') !!}><a href="{{ route('attachmentLibrary') }}"><i class="fa fa-book"></i>{{ trans_choice('pulsar::pulsar.library', 2) }}</a></li>
                    @endif
                </ul>
            </li>
        @endif
        @if(is_allowed('admin-perm', 'access'))
            <li{!! is_current_resource(['admin-perm-profile', 'admin-perm-resource', 'admin-perm-action', 'admin-perm-profile', 'admin-perm-perm'], true) !!}>
                <a href="javascript:void(0)"><i class="fa fa-shield"></i>{{ trans_choice('pulsar::pulsar.permission', 2) }}<span class="arrow"></span></a>
                <ul class="sub-menu">
                    @if(is_allowed('admin-perm-profile', 'access'))
                        <li{!! is_current_resource(['admin-perm-profile', 'admin-perm-perm']) !!}><a href="{{ route('profile') }}"><i class="icomoon-icon-users-2"></i>{{ trans_choice('pulsar::pulsar.profile', 2) }}</a></li>
                    @endif
                    @if(is_allowed('admin-perm-resource', 'access'))
                        <li{!! is_current_resource('admin-perm-resource') !!}><a href="{{ route('resource') }}"><i class="icomoon-icon-database"></i>{{ trans_choice('pulsar::pulsar.resource', 2) }}</a></li>
                    @endif
                    @if(is_allowed('admin-perm-action', 'access'))
                        <li{!! is_current_resource('admin-perm-action') !!}><a href="{{ route('action') }}"><i class="fa fa-bolt"></i>{{ trans_choice('pulsar::pulsar.action', 2) }}</a></li>
                    @endif
                </ul>
            </li>
        @endif
    </ul>
</li>
