<script type="text/javascript">
    function deleteElement(id){
        var url = "{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/paises/destroy/"+id;
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
                        { 'bSortable': false, 'aTargets': [8,9]},
                        { 'sClass': 'checkbox-column', 'aTargets': [8]},
                        { 'sClass': 'align-center', 'aTargets': [9]}
                ],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/paises/json/data"
            }).fnSetFilteringDelay();
            @include('pulsar::pulsar.pulsar.common.js.script_button_delete')
        }
    });
</script>
