<form action="{{ url('admin/change_member_status') }}" method="POST">
	@csrf
	<input type="hidden" name="member" value="{{ $member->id }}">
	<div class="modal-body">
	<div class="form-group">
		<label>Status</label>
		<select class="form-control form-select" name="status">
			@if($member->status == "Inactive")
				<option selected disabled>Select Member Account Status</option>
				<option value="Active">Activate Account</option>
			@elseif($member->status == "Active")
				<option value="Suspended">Suspend Account</option>
			@else
				<option value="Active">Unsuspend Account</option>
			@endif
		</select>
	</div>
	<div>
		<label>Comment</label>
		<textarea class="form-control" rows="4" name="comment"></textarea>
	</div>
</div>
<div class="modal-footer text-end">
	<button type="submit" class="btn btn-danger">Confirm Action</button>
</div>
</form>