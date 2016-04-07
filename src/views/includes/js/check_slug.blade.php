<!-- pulsar::includes.js.check_slug -->
<script>
    $.checkSlug = function() {
        $.ajax({
            dataType:   'json',
            type:       'POST',
            url:        '{{ route($route) }}',
            data:       {
                // if need lang to check slug
                @if(isset($lang))lang:   '{{ $lang }}',@endif
                slug:   $('[name=slug]').val(),
                id:     $('[name=id]').val()
            },
            headers:  {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success:  function(data)
            {
                $("[name=slug]").val(data.slug);
            }
        });
    }
</script>
<!-- /.pulsar::includes.js.check_slug -->