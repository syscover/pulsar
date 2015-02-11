<script type="text/javascript">
    $(document).ready(function() {
        @if(isset($object->image_001))
        $('#inputFile').hide();
        @else
        $('#inputImage').hide();
        @endif
            
        $("form").submit(function() {
            if($('input[name="base"]').is(':checked')) $('input[name="base"]').attr("disabled", false);
        });
    });
    
    @include('pulsar::common.js.script_delete_image')
</script>