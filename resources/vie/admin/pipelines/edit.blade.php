
<div class="my">
    <div class="rw">
        <div class="form-group col-12">
            {{ Form::label('name', __('Pipeline Name'),['class'=>'form-label']) }}
            {{ Form::text('name', $pipeline->name, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>