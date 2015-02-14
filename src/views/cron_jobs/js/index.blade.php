@include('pulsar::common.js.script_success_message')
@include('pulsar::common.js.script_datatable_config')
<script type="text/javascript">
    $(document).ready(function() {
        if ($.fn.dataTable)
        {
            $('.datatable-pulsar').dataTable({
                'iDisplayStart' : {{ $offset }},
                'aoColumnDefs': [
                    { 'bSortable': false, 'aTargets': [8,9]},
                    { 'sClass': 'checkbox-column', 'aTargets': [8]},
                    { 'sClass': 'align-center', 'aTargets': [5,9]}
                ],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ route('jsonData' . $routeSuffix) }}"
            }).fnSetFilteringDelay();
        }
    });
</script>
