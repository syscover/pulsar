@extends('pulsar::layouts.form', ['action' => 'store', 'customTrans' => $country->territorial_area_2_002])

@section('rows')
    <!-- pulsar::territorial_areas_2.create -->
    @include('pulsar::common.block.block_form_text_group', ['label' => 'ID', 'name' => 'id', 'value' => Input::old('id'), 'maxLength' => '10', 'rangeLength' => '2,10', 'required' => true, 'sizeField' => 2])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans_choice('pulsar::pulsar.country',1), 'name' => 'country', 'value' => $country->name_002, 'sizeField' => 2, 'readOnly' => true])
    @include('pulsar::common.block.block_form_text_group', ['label' => trans('pulsar::pulsar.name'), 'name' => 'name', 'value' => Input::old('name'), 'maxLength' => '50', 'rangeLength' => '2,50', 'required' => true])
    <!-- /pulsar::territorial_areas_2.create -->
@stop


@section('mainContentXXX')

    <div class="form-group">
        <label class="col-md-2 control-label"><?php echo $pais->area_territorial_1_002 ?> <span class="required">*</span></label>
        <div class="col-md-2">
            <select class="form-control" name="areaTerritorial1" notequal="null">
                <option value="null">Elija un/a <?php echo $pais->area_territorial_1_002; ?></option>
                <?php foreach ($areasTerritoriales1 as $areaTerritorial1): ?>
                <option value="<?php echo $areaTerritorial1->id_003 ?>" <?php if(Input::old('areaTerritorial1') == $areaTerritorial1->id_003) echo 'selected=""'; ?>><?php echo $areaTerritorial1->nombre_003 ?></option>
                <?php endforeach; ?>
            </select>
            <?php echo $errors->first('modulo',config('pulsar.errorDelimiters')); ?>
        </div>
    </div>
@stop