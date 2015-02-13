@include('pulsar::common.js.script_datatable_config')
<script type="text/javascript">
    @include('pulsar::common.js.script_delete_records')
    
    $(document).ready(function() {
        @include('pulsar::common.js.script_success_message')

        if ($.fn.dataTable) {
            $('.datatable-pulsar').dataTable({
                'iDisplayStart' : {{ $offset }},
                'aoColumnDefs': [
                        { 'bSortable': false, 'aTargets': [6,7]},
                        { 'sClass': 'align-center', 'aTargets': [3]},
                        { 'sClass': 'align-center', 'aTargets': [4]},
                        { 'sClass': 'align-center', 'aTargets': [5]},
                        { 'sClass': 'checkbox-column', 'aTargets': [6]},
                        { 'sClass': 'align-center', 'aTargets': [7]}
                ],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ route('jsonDataLang') }}"
            }).fnSetFilteringDelay();

        }
    });
</script>
