<script type="text/javascript">
    function deleteElement(pais,id){
        var url = "{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/areasterritoriales2/destroy/"+pais+"/"+id;
        @include('pulsar::pulsar.pulsar.common.js.script_delete_element')
    }
    
    @include('pulsar::pulsar.pulsar.common.js.script_delete_elements')
    
    $(document).ready(function() {
        @include('pulsar::pulsar.pulsar.common.js.script_success_mensaje')
        @include('pulsar::pulsar.pulsar.common.js.script_config_datatable')
        if ($.fn.dataTable) {
            $('.datatable-pulsar').dataTable({
                'iDisplayStart' : {{ $inicio }},
                'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [3,4]},
                        { 'sClass': 'checkbox-column', 'aTargets': [3]},
                        { 'sClass': 'align-center', 'aTargets': [4]}
                ],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/areasterritoriales2/json/data/pais/{{ $pais->id_002 }}"
            }).fnSetFilteringDelay();
            @include('pulsar::pulsar.pulsar.common.js.script_button_delete')
        }
    });
</script>
