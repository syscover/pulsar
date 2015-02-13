<script type="text/javascript">
    function deleteElement(id){
        var url = "{{ url(config('pulsar.appName')) }}/pulsar/usuarios/destroy/"+id;
        @include('pulsar::common.js.script_delete_record')
    }
    
    @include('pulsar::common.js.script_delete_records')
    
    $(document).ready(function() {
        @include('pulsar::common.js.script_success_message')
        @include('pulsar::common.js.script_config_datatable')
        if ($.fn.dataTable) {
            $('.datatable-pulsar').dataTable({
                'iDisplayStart' : {{ $offset }},
                'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [6,7]},
                        { 'sClass': 'align-center', 'aTargets': [5]},
                        { 'sClass': 'checkbox-column', 'aTargets': [6]},
                        { 'sClass': 'align-center', 'aTargets': [7]}
                ],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ url(config('pulsar.appName')) }}/pulsar/usuarios/json/data"
            }).fnSetFilteringDelay();

        }
    });
</script>