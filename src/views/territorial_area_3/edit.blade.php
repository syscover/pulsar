@extends('pulsar::layouts.form', ['action' => 'update'])

@section('head')
    @parent
    <!-- pulsar::territorial_areas_3.create -->
    <script type="text/javascript">
        $(document).ready(function() {
            $("[name='territorialArea1']").change(function() {

                $("[name='territorialArea2'] option").remove();
                $("[name='territorialArea2']").append(new Option('{{ trans('pulsar::pulsar.select_a') }} {{ $country->territorial_area_2_002 }}', 'null'));

                if($(this).val())
                {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('jsonTerritorialArea2', [$urlParameters['country']]) }}/" + $(this).val(),
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function(data) {
                            for(var i in data)
                            {
                                $("[name='territorialArea2']").append(new Option(data[i].name_004, data[i].id_004));
                            }
                        }
                    });
                }
            });
        });
    </script>
    <!-- /pulsar::territorial_areas_3.create -->
@stop

@section('rows')
    <!-- pulsar::territorial_areas_3.create -->
    @include('pulsar::includes.html.form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => $object->id_005, 'maxLength' => '10', 'rangeLength' => '2,10', 'required' => true, 'fieldSize' => 2])
    @include('pulsar::includes.html.form_text_group', ['label' => trans_choice('pulsar::pulsar.country',1), 'name' => 'country', 'value' => $country->name_002, 'fieldSize' => 2, 'readOnly' => true])
    @include('pulsar::includes.html.form_select_group', ['label' => $country->territorial_area_1_002, 'name' => 'territorialArea1', 'value' => $object->territorial_area_1_005, 'fieldSize' => 6, 'objects' => $territorialAreas1, 'idSelect' => 'id_003', 'nameSelect' => 'name_003', 'required' => true])
    @include('pulsar::includes.html.form_select_group', ['label' => $country->territorial_area_2_002, 'name' => 'territorialArea2', 'value' => $object->territorial_area_2_005, 'fieldSize' => 6, 'objects' => $territorialAreas2, 'idSelect' => 'id_004', 'nameSelect' => 'name_004', 'required' => true])
    @include('pulsar::includes.html.form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => $object->name_005, 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    <!-- /pulsar::territorial_areas_3.create -->
@stop