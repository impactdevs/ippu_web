<div class="card">
	<div class="card-body">
		<div class="mb-3">
	<label>User</label>
	<input type="text" name="" class="form-control" value="{{ $user->name }}" disabled>
	<input type="hidden" class="user_id" value="{{ $user->id }}">
</div>
<div class="row mb-3">
	<div class="col-md-12">
		<h5>Events</h5>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="create_event" type="checkbox" value="create event" {{ ($user->can('create event')) ? 'checked' : '' }}>
		<label for="create_event">Create Events</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="update_event" type="checkbox" value="update event" {{ ($user->can('update event')) ? 'checked' : '' }}>
		<label for="update_event">Edit Events</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="show_event" type="checkbox" value="show event" {{ ($user->can('show event')) ? 'checked' : '' }}>
		<label for="show_event">Show Events</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="delete_event" type="checkbox" value="delete event" {{ ($user->can('delete event')) ? 'checked' : '' }}>
		<label for="create_event">Delete Events</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="approve_event_attendence" type="checkbox" value="approve event attendence" {{ ($user->can('approve event attendence')) ? 'checked' : '' }}>
		<label for="approve_event_attendence">Approve Event Attendence</label>
	</div>

</div>
<div class="row mb-3">
	<div class="col-md-12">
		<h5>CPDs</h5>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="create_CPD" type="checkbox" value="create CPD" {{ ($user->can('create CPD')) ? 'checked' : '' }}>
		<label for="create_CPD">Create CPDs</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="update_CPD" type="checkbox" value="update CPD" {{ ($user->can('update CPD')) ? 'checked' : '' }}>
		<label for="update_CPD">Edit CPDs</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="show_CPD" type="checkbox" value="show CPD" {{ ($user->can('show CPD')) ? 'checked' : '' }}>
		<label for="show_CPD">Show CPDs</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="delete_CPD" type="checkbox" value="delete CPD" {{ ($user->can('delete CPD')) ? 'checked' : '' }}>
		<label for="create_CPD">Delete CPDs</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="approve_CPD_attendence" type="checkbox" value="approve CPD attendence" {{ ($user->can('approve CPD attendence')) ? 'checked' : '' }}>
		<label for="approve_CPD_attendence">Approve CPD Attendence</label>
	</div>

</div>
<div class="row mb-3">
	<div class="col-md-12">
		<h5>Accounts</h5>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="invoicing" type="checkbox" value="invoice" {{ ($user->can('invoice')) ? 'checked' : '' }}>
		<label for="invoicing">Invoicing</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="expensing" type="checkbox" value="expense" {{ ($user->can('expense')) ? 'checked' : '' }}>
		<label for="expensing">Expenses</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="inventory" type="checkbox" value="inventory" {{ ($user->can('show CPD')) ? 'checked' : '' }}>
		<label for="inventory">Inventory</label>
	</div>

</div>
<div class="row mb-3">
	<div class="col-md-12">
		<h5>HRN</h5>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="hrm" type="checkbox" value="hrm" {{ ($user->can('hrm')) ? 'checked' : '' }}>
		<label for="hrm">HRM</label>
	</div>
</div>
<div class="row mb-3">
	<div class="col-md-12">
		<h5>CRM</h5>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="leads" type="checkbox" value="leads" {{ ($user->can('leads')) ? 'checked' : '' }}>
		<label for="leads">Leads</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="deals" type="checkbox" value="deals" {{ ($user->can('deals')) ? 'checked' : '' }}>
		<label for="deals">Deals</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="form_builder" type="checkbox" value="form builder" {{ ($user->can('form builder')) ? 'checked' : '' }}>
		<label for="form_builder">Form builder</label>
	</div>
</div>
<div class="row mb-3">
	<div class="col-md-12">
		<h5>Admin</h5>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="members" type="checkbox" value="members" {{ ($user->can('members')) ? 'checked' : '' }}>
		<label for="members">Show Members</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="make_admin" type="checkbox" value="make admin" {{ ($user->can('make admin')) ? 'checked' : '' }}>
		<label for="make_admin">Make Admin</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="approve_members" type="checkbox" value="approve members" {{ ($user->can('approve members')) ? 'checked' : '' }}>
		<label for="approve_members">Approve Members</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="communications" type="checkbox" value="communications" {{ ($user->can('communications')) ? 'checked' : '' }}>
		<label for="communications">Communications</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="audit_trail" type="checkbox" value="audit trail" {{ ($user->can('audit trail')) ? 'checked' : '' }}>
		<label for="audit_trail">Audit Trail</label>
	</div>
	<div class="col-md-4">
		<input class="form-check-input permission" id="settings" type="checkbox" value="settings" {{ ($user->can('settings')) ? 'checked' : '' }}>
		<label for="audit_trail">Settings</label>
	</div>

</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(".permission").click(function(){
			var is_checked = ($(this).is(':checked')) ? true : false;
			var permission = $(this).val();
			var user = $(".user_id").val();

			$.ajax({
				url: "{{ url('admin/assign_permission') }}",
				type: "POST",
				data: "user="+user+"&permission="+permission+"&assigned="+is_checked,

				success:function(data){
					// alert(data)
				}
			})
		})
	})
</script>
	</div>
</div>