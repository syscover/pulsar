@extends('pulsar::layouts.form', ['action' => 'update'])

@section('script')
    @parent
            <!-- comunik::contacts.create -->
    <link href="{{ asset('packages/syscover/pulsar/css/custom/select2/select2.css') }}" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/bootstrap-inputmask/jquery.inputmask.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/plugins/select2/select2_locale_' . config('app.locale') . '.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/syscover/pulsar/vendor/getaddress/js/jquery.getaddress.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('[name="country"]').data('language', '{{ config('app.locale') }}')

            $.getAddress({
                id:                         '01',
                type:                       'laravel',
                appName:                    'pulsar',
                token:                      '{{ csrf_token() }}',
                lang:                       '{{ config('app.locale') }}',
                highlightCountrys:          ['ES','US'],

                useSeparatorHighlight:      true,
                textSeparatorHighlight:     '------------------',

                countryValue:               '{{ $object->country_030 }}'
            });
        });
    </script>
    <!-- comunik::contacts.create -->
@stop

@section('rows')
    <!-- comunik::contacts.create -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'value' => $object->id_030, 'name' => 'id', 'readOnly' => true, 'fieldSize' => 2])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.group', 2), 'value' => $object->groups, 'name' => 'groups[]', 'objects' => $groups, 'idSelect' => 'id_029', 'nameSelect' => 'name_029', 'multiple' => true, 'class' => 'select2', 'fieldSize' => 10, 'data' => ['placeholder' => 'Seleccione las categorías correspondientes']])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.company'), 'name' => 'company', 'value' => $object->company_030, 'maxLength' => '100', 'rangeLength' => '2,100'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_030, 'maxLength' => '50', 'rangeLength' => '2,50'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.surname'), 'name' => 'surname', 'value' => $object->surname_030, 'maxLength' => '50', 'rangeLength' => '2,50'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.birthdate'), 'name' => 'birthdate', 'value' => $object->birthdate_030? date('d-m-Y', $object->birthdate_030) : null, 'fieldSize' => 2, 'data' => ['mask' => '99-99-9999']])
    @include('pulsar::includes.html.form_select_group', ['label' => trans_choice('pulsar::pulsar.country', 1), 'name' => 'country', 'idSelect' => 'id_002', 'nameSelect' => 'name_002', 'class' => 'select2', 'fieldSize' => 4])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.email'), 'name' => 'email', 'value' => $object->email_030, 'maxLength' => '50', 'rangeLength' => '2,50', 'type' => 'email'])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.mobile'), 'name' => 'prefix', 'value' => $object->prefix_030, 'maxLength' => '5', 'rangeLength' => '0,5', 'fieldSize' => 2, 'placeholder' => trans('comunik::pulsar.international_prefix'), 'inputs' => [['name' => 'mobile', 'value' => $object->mobile_030, 'maxLength' => '50', 'rangeLength' => '2,50', 'fieldSize' => 8]]])
    <!-- /comunik::contacts.create -->
@stop