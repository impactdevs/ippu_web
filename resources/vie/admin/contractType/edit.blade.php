<div class="modal-boedy">
    <div class="rosw">
        <div class="form-group">
            {{ Form::label('name', __('Name')) }}
            {{ Form::text('name', $contractType->name, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>

