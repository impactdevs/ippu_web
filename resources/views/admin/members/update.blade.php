<form action="{{ url('admin/update_member_details') }}" method="POST">
	@csrf
	<input type="hidden" name="member" value="{{ $member->id }}">
	{{-- {{ $member }} --}}
	<div class="modal-body">
		<div class="form-group mb-3">
			<label>Name</label>
			<input type="text" class="form-control" name="name" value="{{ $member->name }}">
		</div>
		<div class="form-group mb-3">
			<label>Membership Number</label>
			<input type="text"  class="form-control" name="membership_number" value="{{ $member->membership_number }}">
		</div>
		<div class="form-group mb-3">
			<label>Email</label>
			<input type="text" class="form-control" name="email" value="{{ $member->email }}">
		</div>
		<div class="form-group mb-3">
			<label>Gender</label>
			<select class="form-control form-select" name="gender">
				<option value="Male" {{ ($member->gender == "Male") ? 'selected' : '' }}>Male</option>
				<option value="Female" {{ ($member->gender == "Female") ? 'selected' : '' }}>Female</option>
			</select>
		</div>
		<div class="form-group mb-3">
			<label>Organisation</label>
			<input type="text" class="form-control" name="organisation" value="{{ $member->organisation }}">
		</div>
	<div class="form-group">
		<label>Account Type</label>
		<select class="form-control form-select" name="account_type">
			@foreach($account_types as $account_type)
				<option value="{{ $account_type->id }}" {{ ($account_type->id == $member->account_type_id) ? 'selected' : '' }}>{{ $account_type->name }}</option>
			@endforeach
		</select>
	</div>
</div>
<div class="modal-footer text-end">
	<button type="submit" class="btn btn-danger">Update Member Details</button>
</div>
</form>