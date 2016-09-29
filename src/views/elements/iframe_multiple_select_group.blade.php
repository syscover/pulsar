{{--<div class="form-group">--}}
    {{--<label class="col-md-{{ $labelSize or 2 }} control-label">{{ $label }} @if(isset($required) && $required) @include('pulsar::elements.required') @endif</label>--}}
    {{--<div class="col-md-{{ $fieldSize or 8 }}">--}}
        {{--<input class="form-control" type="{{ isset($type)? $type : 'text' }}" name="{{ $name }}" value="{{ isset($value)? $value : null }}"{!! isset($data)? Miscellaneous::setDataAttributes($data) : null !!} @if(isset($placeholder)) placeholder="{{ $placeholder }}"@endif @if(isset($maxLength)) maxlength="{{ $maxLength }}"@endif @if(isset($rangeLength))rangelength="{{ $rangeLength }}"@endif @if(isset($min)) min="{{ $min }}"@endif{{ isset($readOnly) && $readOnly || ! isset($readOnly)? ' readonly' : null }}{{ isset($required) && $required? ' required' : null }}>--}}
        {{--{!! $errors->first($name . 'Id', config('pulsar.errorDelimiters')) !!}--}}
    {{--</div>--}}
    {{--<div class="col-md-{{ $iconSize or 2 }}">--}}
        {{--@if(! isset($disabled) ||  isset($disabled) && ! $disabled)--}}
            {{--<a href="{{ $modalUrl }}" class="magnific-popup"><span class="black-text fa-2x fa fa-external-link"></span></a>--}}
        {{--@endif--}}
        {{--<input type="hidden" name="{{ $name . 'Id' }}" value="{{ isset($valueId)? $valueId : null }}"{{ isset($required) && $required? ' required' : null }}>--}}
    {{--</div>--}}
{{--</div>--}}

@section('head')
    @parent
    <script>
        {{--$(document).ready(function() {--}}
            {{--$.elementTable({--}}
                {{--id: '{{ $id }}',--}}
                {{--lang: {--}}
                    {{--editRecord:     '{{ trans("pulsar::pulsar.edit") }}',--}}
                    {{--deleteRecord:   '{{ trans("pulsar::pulsar.delete") }}'--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}
    </script>
@stop

<a class="btn btn-info margin-b10 magnific-popup" id="{{ $id }}Bt" href="{{ $modalUrl }}"><i class="{{ $icon }}"></i> {{ $label }}</a>

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
    <!-- popup -->
    <div id="{{ $id }}Popup" class="container white-popup mfp-hide">
        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header"><h4><i class="{{ $icon }}"></i> {{ $label }}</h4></div>
                    <div class="widget-content">
                        <form id="{{ $id }}Form" class="form-horizontal">
                            <input type="hidden" name="{{ $id }}Index">
                            <?php unset($id) ?>
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
    <!-- /popup -->
@stop