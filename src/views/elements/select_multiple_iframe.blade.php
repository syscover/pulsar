<a class="btn btn-info margin-b10 magnific-popup" href="{{ $modalUrl }}"><i class="{{ $icon }}"></i> {{ $label }}</a>
<input type="hidden" name="{{ $id }}Data" value="{{ $dataJson or '[]' }}">
<table id="{{ $id }}" class="table table-hover table-striped">
    <thead>
    <tr>
        @foreach($thead as $object)
            <th @if(isset($object->class))class="{{ $object->class }}"@endif>{{ $object->data }}</th>
        @endforeach
        <th class="align-center">{{ trans_choice("pulsar::pulsar.action", 2) }}</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>