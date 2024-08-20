
<div class="modal-boxdy">
    <div class="rodw">
        <div class="form-group codl-12">
            {{ Form::label('name', __('Source Name'),['class'=>'form-label']) }}
            {{ Form::text('name', $source->name, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
</div>
