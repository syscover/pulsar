<script type="text/javascript">
    function deleteElement(pais,id){
        var url = "{{ url(config('pulsar.appName')) }}/pulsar/areasterritoriales3/destroy/"+pais+"/"+id;
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
                        { 'bSortable': false, 'aTargets': [4,5]},
                        { 'sClass': 'checkbox-column', 'aTargets': [4]},
                        { 'sClass': 'align-center', 'aTargets': [5]}
                ],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ url(config('pulsar.appName')) }}/pulsar/areasterritoriales3/json/data/pais/{{ $pais->id_002 }}"
            }).fnSetFilteringDelay();

        }
    });
</script>
