<script type="text/javascript">
    function deleteElement(id){
        var url = "{{ url(config('pulsar.appName') . '/pulsar/modulos/destroy/') }}/" + id;
        @include('pulsar::common.js.script_delete_record')
    }
    
    @include('pulsar::common.js.script_delete_records')
    
    $(document).ready(function() {
        @include('pulsar::common.js.script_success_message')
        @include('pulsar::common.js.script_config_datatable')
        if ($.fn.dataTable)
        {
            $('.datatable-pulsar').dataTable({
                'iDisplayStart' : {{ $offset }},
                'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [3,4]},
                        { 'sClass': 'align-center', 'aTargets': [2]},
                        { 'sClass': 'checkbox-column', 'aTargets': [3]},
                        { 'sClass': 'align-center', 'aTargets': [4]}
                ],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ url(config('pulsar.appName') . '/pulsar/packages/json/data') }}"
            }).fnSetFilteringDelay();

        }
    });
</script>
