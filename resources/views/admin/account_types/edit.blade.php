<div class="card-body">
    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" name="name" id="name" class="form-control" value="{{@old('name', $accountType->name)}}" required/>
        @if($errors->has('name'))
        <div class='error small text-danger'>{{$errors->first('name')}}</div>
        @endif
    </div>
    <div class="mb-3">
        <label for="rate" class="form-label">Rate:</label>
        <input type="text" name="rate" id="rate" class="form-control" value="{{@old('rate', $accountType->rate)}}" required/>
        @if($errors->has('rate'))
        <div class='error small text-danger'>{{$errors->first('rate')}}</div>
        @endif
    </div>
    <div class="mb-3">
        <label for="is_active" class="form-label">Is Active:</label>
        <div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_active" id="is_active_yes" value="1" {{ @old('is_active', $accountType->is_active) == '1' ? 'checked' : '' }} required>
                <label class="form-check-label" for="is_active_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_active" id="is_active_no" value="0" {{ @old('is_active', $accountType->is_active) == '0' ? 'checked' : '' }} required>
                <label class="form-check-label" for="is_active_no">No</label>
            </div>
        </div>
        @if($errors->has('is_active'))
        <div class='error small text-danger'>{{$errors->first('is_active')}}</div>
        @endif
    </div>

    <div class="mb-3">
        <label for="rate" class="form-label">Description:</label>
        <textarea class="form-control" name="description" required>{{ $accountType->description }}</textarea>
        @if($errors->has('description'))
        <div class='error small text-danger'>{{$errors->first('description')}}</div>
        @endif
    </div>

</div>