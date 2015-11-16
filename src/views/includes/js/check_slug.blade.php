<!-- pulsar::includes.js.check_slug -->
<script type="text/javascript">
    $.checkSlug = function() {
        $.ajax({
            dataType:   'json',
            type:       'POST',
            url:        '{{ route($route) }}',
            data:       {
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
<!-- /pulsar::includes.js.check_slug -->