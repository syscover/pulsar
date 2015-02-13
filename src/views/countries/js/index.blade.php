<script type="text/javascript">
    function deleteElement(id){
        var url = "{{ url(config('pulsar.appName')) }}/pulsar/paises/destroy/"+id;
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
                        { 'bSortable': false, 'aTargets': [8,9]},
                        { 'sClass': 'checkbox-column', 'aTargets': [8]},
                        { 'sClass': 'align-center', 'aTargets': [9]}
                ],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ url(config('pulsar.appName')) }}/pulsar/paises/json/data"
            }).fnSetFilteringDelay();

        }
    });
</script>
