<?php $aux = Session::get('packages'); ?>
<ul id="nav">

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'hotels', 'access') && $aux[13]->active_012 && View::exists('hotels::pulsar.hotels.common.block.block_navigation'))
        @include('hotels::pulsar.hotels.common.block.block_navigation')
    @endif

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'soportespub', 'access') && $aux[10]->active_012 && View::exists('soportespublicitarios::pulsar.soportespublicitarios.common.block.block_navigation'))
        @include('soportespublicitarios::pulsar.soportespublicitarios.common.block.block_navigation')
    @endif
        
    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'vinipadsalesforce', 'access') && $aux[7]->active_012 && View::exists('vinipadsalesforce::pulsar.vinipadsalesforce.common.block.block_navigation'))
        @include('vinipadsalesforce::pulsar.vinipadsalesforce.common.block.block_navigation')
    @endif

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'vinipadcc', 'access') && $aux[9]->active_012 && View::exists('vinipadcuadernocata::pulsar.vinipadcuadernocata.common.block.block_navigation'))
        @include('vinipadcuadernocata::pulsar.vinipadcuadernocata.common.block.block_navigation')
    @endif

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'vinipad', 'access') && $aux[3]->active_012 && View::exists('vinipad::pulsar.vinipad.common.block.block_navigation'))
        @include('vinipad::pulsar.vinipad.common.block.block_navigation')
    @endif

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'comunik', 'access') && $aux[5]->active_012 && View::exists('comunik::pulsar.comunik.common.block.block_navigation'))
        @include('comunik::pulsar.comunik.common.block.block_navigation')
    @endif

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'cabinas', 'access') && $aux[4]->active_012 && View::exists('cabinas::pulsar.cabinas.common.block.block_navigation'))
        @include('cabinas::pulsar.cabinas.common.block.block_navigation')
    @endif

    @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin', 'access') && $aux[2]->active_012)
        <li{{ Pulsar\Pulsar\Libraries\Miscellaneous::setCurrenOpenPage(array('admin-user', 'admin-lang', 'admin-country', 'admin-country-at1', 'admin-country-at2', 'admin-country-at3', 'admin-mod', 'admin-cron', 'admin-pass-profile', 'admin-pass-resource', 'admin-pass-actions', 'admin-pass-pass')) }}>
            <a href="javascript:void(0);"><i class="icon-cog"></i>Administración</a>
            <ul class="sub-menu">
                @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-user', 'access'))
                    <li{{ Pulsar\Pulsar\Libraries\Miscellaneous::setCurrentPage('admin-user') }}><a href="{{ url(config('pulsar.appName') . '/pulsar/users') }}"><i class="icomoon-icon-users"></i>Usuarios</a></li>
                @endif
                @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-lang', 'access'))
                        <li{{ Pulsar\Pulsar\Libraries\Miscellaneous::setCurrentPage('admin-lang') }}><a href="{{ url(config('pulsar.appName') . '/pulsar/languages') }}"><i class="brocco-icon-flag"></i>{{ trans('pulsar::pulsar.languages') }}</a></li>
                @endif
                @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-country', 'access'))
                        <li{{ Pulsar\Pulsar\Libraries\Miscellaneous::setCurrentPage(array('admin-country','admin-country-at1','admin-country-at2','admin-country-at3')) }}><a href="{{ url(config('pulsar.appName') . '/pulsar/countries') }}"><i class="entypo-icon-globe"></i>Países</a></li>
                @endif
                @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-mod', 'access'))
                        <li{{ Pulsar\Pulsar\Libraries\Miscellaneous::setCurrentPage('admin-mod') }}><a href="{{ url(config('pulsar.appName') . '/pulsar/packages') }}"><i class="cut-icon-grid"></i>Paquetes</a></li>
                @endif
                @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-cron', 'access'))
                        <li{{ Pulsar\Pulsar\Libraries\Miscellaneous::setCurrentPage('admin-cron') }}><a href="{{ url(config('pulsar.appName') . '/pulsar/cron/jobs') }}"><i class="icomoon-icon-stopwatch"></i>Tareas Cron</a></li>
                @endif
                @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-pass', 'access'))
                    <li{{ Pulsar\Pulsar\Libraries\Miscellaneous::setOpenPage(array('admin-pass-profile', 'admin-pass-resource', 'admin-pass-actions', 'admin-pass-profile', 'admin-pass-pass')) }}>
                        <a href="javascript:void(0);"><i class="icon-user"></i>Permisos<span class="arrow"></span></a>
                        <ul class="sub-menu"{{Pulsar\Pulsar\Libraries\Miscellaneous::setDisplayPage(array('admin-pass-resource', 'admin-pass-actions', 'admin-pass-profile', 'admin-pass-pass')) }}>
                            @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-pass-profile', 'access'))
                                <li{{ Pulsar\Pulsar\Libraries\Miscellaneous::setCurrentPage(array('admin-pass-profile', 'admin-pass-pass')) }}><a href="{{ url(config('pulsar.appName')) }}/pulsar/profiles"><i class="icomoon-icon-users-2"></i>Perfiles</a></li>
                            @endif
                            @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-pass-resource', 'access'))
                                <li{{ Pulsar\Pulsar\Libraries\Miscellaneous::setCurrentPage('admin-pass-resource') }}><a href="{{ url(config('pulsar.appName') . '/pulsar/resources') }}"><i class="icomoon-icon-database"></i>Recursos</a></li>
                            @endif
                            @if(Session::get('userAcl')->isAllowed(Auth::user()->profile_010, 'admin-pass-actions', 'access'))
                                <li{{ Pulsar\Pulsar\Libraries\Miscellaneous::setCurrentPage('admin-pass-actions') }}><a href="{{ url(config('pulsar.appName') . '/pulsar/actions') }}"><i class="icomoon-icon-power"></i>Acciones</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </li>
    @endif
</ul>