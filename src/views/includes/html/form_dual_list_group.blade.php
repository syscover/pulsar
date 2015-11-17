<div class="widget-content clearfix">
    <!-- Left box -->
    <div class="left-box">
        <label>{{ $labelList1 or trans('pulsar::pulsar.objects_list') }}</label>
        <input type="text" id="box{{ $idList1 }}Filter" class="form-control box-filter" placeholder="{{ trans('pulsar::pulsar.filter_records') }}"><button type="button" id="box{{ $idList1 }}Clear" class="filter">x</button>
        <select id="box{{ $idList1 }}View" multiple="multiple" class="multiple">
            @foreach ($objects as $object)
                @if(isset($objectsSelect))
                    @if(!in_array($object->{$idSelect}, $objectsSelect->pluck($idSelect)->toArray()))
                    <option value="{{ $object->{$idSelect} }}">{{ $object->{$nameSelect} }}</option>
                    @endif
                @else
                    <option value="{{ $object->{$idSelect} }}">{{ $object->{$nameSelect} }}</option>
                @endif
            @endforeach
        </select>
        <span id="box{{ $idList1 }}Counter" class="count-label"></span>
        <select id="box{{ $idList1 }}Storage"></select>
    </div>
    <!--left-box -->

    <!-- Control buttons -->
    <div class="dual-control">
        <button id="to{{ $idList2 }}" type="button" class="btn">&nbsp;&gt;&nbsp;</button>
        <button id="allTo{{ $idList2 }}" type="button" class="btn">&nbsp;&gt;&gt;&nbsp;</button><br>
        <button id="to{{ $idList1 }}" type="button" class="btn">&nbsp;&lt;&nbsp;</button>
        <button id="allTo{{ $idList1 }}" type="button" class="btn">&nbsp;&lt;&lt;&nbsp;</button>
    </div>
    <!--control buttons -->

    <!-- Right box -->
    <div class="right-box">
        <label>{{ $labelList2 or trans('pulsar::pulsar.selected_objects') }}</label>
        <input type="text" id="box{{ $idList2 }}Filter" class="form-control box-filter" placeholder="{{ trans('pulsar::pulsar.filter_records') }}"><button type="button" id="box{{ $idList2 }}Clear" class="filter">x</button>
        <select id="box{{ $idList2 }}View" multiple="multiple" class="multiple dual-list-submit" name="{{ $name }}[]"{{ isset($required) && $required? ' required' : null }}>
            @if(isset($objectsSelect))
                @foreach ($objectsSelect as $object)
                    <option value="{{ $object->{$idSelect} }}">{{ $object->{$nameSelect} }}</option>
                @endforeach
            @endif
        </select>
        <span id="box{{ $idList2 }}Counter" class="count-label"></span>
        <select id="box{{ $idList2 }}Storage"></select>
    </div>
    <!--right box -->
</div>
{{ $errors->first($name, config('pulsar::pulsar.errorDelimiters')) }}