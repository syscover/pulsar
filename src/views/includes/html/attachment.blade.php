<!-- pulsar::includes.html.attachment -->
@include('pulsar::includes.html.form_hidden', ['name' => 'attachments', 'value' => $attachmentsInput])
<!--
<div class="btn-group margin-b10">
    <button class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-download"></i> Import from library</button>
</div>
-->
<div id="attachment-library" class="widget box">
    <div class="widget-content no-padding">
        <div class="row" id="attachment-wrapper">
            <div id="library-placeholder">
                <p>{{ trans('pulsar::pulsar.drag_files') }}</p>
            </div>
            <ul class="sortable">
                @if(isset($attachments))
                    @foreach($attachments as $attachment)
                        <?php $data = json_decode($attachment->data_016); ?>
                        <li data-id="{{$attachment->id_016}}">
                            @if($action == 'store' || $action == 'storeLang')
                                @include('pulsar::includes.html.form_hidden', ['name' => 'tmpFileName', 'value' => $attachment->tmp_file_name_016])
                            @endif
                            <div class="attachment-item">
                                <div class="attachment-img">
                                    @if($action == 'store' || $action == 'storeLang')
                                        <img{!! $attachment->type_016 == 1? ' class="is-image"' : ' class="no-image"' !!} src="{{ $attachment->type_016 == 1? config($routesConfigFile . '.tmpFolder') . '/' . $attachment->tmp_file_name_016 : config($routesConfigFile . '.iconsFolder') . '/' . $data->icon }}" />
                                    @else
                                        <img{!! $attachment->type_016 == 1? ' class="is-image"' : ' class="no-image"' !!} src="{{ $attachment->type_016 == 1? config($routesConfigFile . '.attachmentFolder') . '/' . $attachment->object_016 . '/' . $attachment->lang_016 . '/' . $attachment->file_name_016 : config($routesConfigFile . '.iconsFolder') . '/' . $data->icon }}" />
                                    @endif
                                </div>
                                <div class="attachment-over">
                                    <div class="col-md-10 col-sm-10 col-xs-10 uncovered">
                                        <h4 class="attachment-title family-name">{{ $attachment->name_015 }}</h4>
                                        <p class="attachment-sub file-name">{{ $attachment->file_name_016 }}</p>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 uncovered">
                                        <h4 class="attachment-action"><span class="glyphicon glyphicon-pencil"></span></h4>
                                    </div>
                                    <div>
                                        <div class="close-icon covered"><span class="glyphicon glyphicon-remove"></span></div>
                                        <div class="col-md-12 col-sm-12 col-xs-12 covered">
                                            <div class="form-group">
                                                <input type="text" class="form-control image-name" placeholder="{{ trans('pulsar::pulsar.image_name') }}" data-previous="{{ $attachment->name_016 }}" value="{{ $attachment->name_016 }}">
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control attachment-family" name="attachmentFamily" data-previous="{{ $attachment->family_016 }}">
                                                    <option value="">{{ trans('pulsar::pulsar.select_family') }}</option>
                                                    @foreach($attachmentFamilies as $attachmentFamily)
                                                        <option value="{{ $attachmentFamily->id_015 }}"{{ $attachment->family_016 == $attachmentFamily->id_015? ' selected' : null }}>{{ $attachmentFamily->name_015 }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12 covered">
                                            <div class="form-group">
                                                <button type="button" class="close-ov form-control save-attachment">{{ trans('pulsar::pulsar.save') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="remove-img">
                                <span class="glyphicon glyphicon-remove"></span>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- /pulsar::includes.html.attachment -->