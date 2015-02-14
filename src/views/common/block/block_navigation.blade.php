<?php $aux = Session::get('packages'); ?>
<ul id="nav">

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'hotels', 'access') && $aux[13]->active_012 && View::exists('hotels::common.block.block_navigation'))
        @include('hotels::pulsar.hotels.common.block.block_navigation')
    @endif

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'soportespub', 'access') && $aux[10]->active_012 && View::exists('soportespublicitarios::common.block.block_navigation'))
        @include('soportespublicitarios::pulsar.soportespublicitarios.common.block.block_navigation')
    @endif
        
    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'vinipadsalesforce', 'access') && $aux[7]->active_012 && View::exists('vinipadsalesforce::common.block.block_navigation'))
        @include('vinipadsalesforce::pulsar.vinipadsalesforce.common.block.block_navigation')
    @endif

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'vinipadcc', 'access') && $aux[9]->active_012 && View::exists('vinipadcuadernocata::common.block.block_navigation'))
        @include('vinipadcuadernocata::pulsar.vinipadcuadernocata.common.block.block_navigation')
    @endif

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'vinipad', 'access') && $aux[3]->active_012 && View::exists('vinipad::common.block.block_navigation'))
        @include('vinipad::pulsar.vinipad.common.block.block_navigation')
    @endif

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'comunik', 'access') && $aux[5]->active_012 && View::exists('comunik::common.block.block_navigation'))
        @include('comunik::pulsar.comunik.common.block.block_navigation')
    @endif

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'cabinas', 'access') && $aux[4]->active_012 && View::exists('cabinas::common.block.block_navigation'))
        @include('cabinas::pulsar.cabinas.common.block.block_navigation')
    @endif

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin', 'access') && $aux[2]->active_012)
        <li{!! Miscellaneous::setCurrentOpenPage(['admin-user', 'admin-lang', 'admin-country', 'admin-country-at1', 'admin-country-at2', 'admin-country-at3', 'admin-packages', 'admin-cron', 'admin-perm-profile', 'admin-perm-resource', 'admin-perm-actions', 'admin-perm-perm']) !!}>
            <a href="javascript:void(0);"><i class="icon-cog"></i>Administración</a>
            <ul class="sub-menu">
                @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-user', 'access'))
                    <li{!! Miscellaneous::setCurrentPage('admin-user') !!}><a href="{{ route('User') }}"><i class="icomoon-icon-users"></i>Usuarios</a></li>
                @endif
                @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-lang', 'access'))
                        <li{!! Miscellaneous::setCurrentPage('admin-lang') !!}><a href="{{ route('Lang') }}"><i class="brocco-icon-flag"></i>{{ trans_choice('pulsar::pulsar.language', 2) }}</a></li>
                @endif
                @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-country', 'access'))
                        <li{!! Miscellaneous::setCurrentPage(array('admin-country','admin-country-at1','admin-country-at2','admin-country-at3')) !!}><a href="{{ route('Country') }}"><i class="entypo-icon-globe"></i>Países</a></li>
                @endif
                @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-packages', 'access'))
                        <li{!! Miscellaneous::setCurrentPage('admin-mod') !!}><a href="{{ route('Package') }}"><i class="cut-icon-grid"></i>Paquetes</a></li>
                @endif
                @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-cron', 'access'))
                        <li{!! Miscellaneous::setCurrentPage('admin-cron') !!}><a href="{{ route('CronJob') }}"><i class="icomoon-icon-stopwatch"></i>Tareas Cron</a></li>
                @endif
                @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm', 'access'))
                    <li{!! Miscellaneous::setOpenPage(['admin-perm-profile', 'admin-perm-resource', 'admin-perm-actions', 'admin-perm-profile', 'admin-perm-perm']) !!}>
                        <a href="javascript:void(0);"><i class="icon-user"></i>{{ trans_choice('pulsar::pulsar.permission', 2) }}<span class="arrow"></span></a>
                        <ul class="sub-menu"{!! Miscellaneous::setDisplayPage(['admin-perm-resource', 'admin-perm-actions', 'admin-perm-profile', 'admin-perm-perm']) !!}>
                            @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm-profile', 'access'))
                                <li{!! Miscellaneous::setCurrentPage(array('admin-perm-profile', 'admin-perm-perm')) !!}><a href="{{ route('Profile') }}"><i class="icomoon-icon-users-2"></i>{{ trans_choice('pulsar::pulsar.profile', 2) }}</a></li>
                            @endif
                            @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm-resource', 'access'))
                                <li{!! Miscellaneous::setCurrentPage('admin-perm-resource') !!}><a href="{{ route('Resource') }}"><i class="icomoon-icon-database"></i>{{ trans_choice('pulsar::pulsar.resource', 2) }}</a></li>
                            @endif
                            @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-perm-actions', 'access'))
                                <li{!! Miscellaneous::setCurrentPage('admin-perm-actions') !!}><a href="{{ route('Action') }}"><i class="icomoon-icon-power"></i>{{ trans_choice('pulsar::pulsar.action', 2) }}</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </li>
    @endif
</ul>