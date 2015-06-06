@section('script')
    @parent

    <script type="text/javascript">
        $(document).ready(function() {
            $.elementTable({
                id: '{{ $id }}',
                lang: {
                    editRecord:     '{{ trans("pulsar::pulsar.edit") }}',
                    deleteRecord:   '{{ trans("pulsar::pulsar.delete") }}'
                }
            });
        });
    </script>
    <!-- forms::forms.create -->
@stop
<a class="btn btn-info marginB10" id="{{ $id }}Bt" href="#"><i class="{{ $icon }}"></i> New {{ $label }}</a>
<input type="hidden" name="{{ $id }}Data" value="{{ $dataJson or '[]' }}">
<input type="hidden" name="{{ $id }}TBody" value="{{ json_encode($tbody) }}">
<table id="{{ $id }}" class="table table-hover table-striped">
    <thead>
        <tr>
            @foreach($thead as $object)
            <th @if(isset($object->class))class="{{ $object->class }}"@endif>{{ $object->data }}</th>
            @endforeach
            <th class="align-center">{{ trans_choice("pulsar::pulsar.action", 2) }}</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

@section('outForm')
    @parent
    <!-- PopUp -->
    <div id="{{ $id }}Popup" class="container white-popup mfp-hide">
        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header"><h4><i class="{{ $icon }}"></i> {{ $label }}</h4></div>
                    <div class="widget-content">
                        <form id="{{ $id }}Form" class="form-horizontal">
                            <input type="hidden" name="{{ $id }}Index">
                            @foreach($tbody as $object)
                                @include($object->include, $object->properties)
                            @endforeach
                            <hr>
                            <div>
                                <a class="btn mfp-cusstom-add">{{ trans("pulsar::pulsar.add") }}</a>
                                <a class="btn mfp-cusstom-update">{{ trans("pulsar::pulsar.update") }}</a>
                                <a class="btn btn-inverse mfp-cusstom-close">{{ trans("pulsar::pulsar.cancel") }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ PopUp -->
@stop