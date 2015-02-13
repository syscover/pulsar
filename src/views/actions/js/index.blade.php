@include('pulsar::common.js.script_success_message')
@include('pulsar::common.js.script_datatable_config')
<script type="text/javascript">
    $(document).ready(function() {
        if ($.fn.dataTable)
        {
            $('.datatable-pulsar').dataTable({
                'iDisplayStart' : {{ $offset }},
                'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [2,3]},
                        { 'sClass': 'checkbox-column', 'aTargets': [2]},
                        { 'sClass': 'align-center', 'aTargets': [3]}
                ],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ route('jsonData' . $routeSuffix) }}"
            }).fnSetFilteringDelay();
        }
    });
</script>