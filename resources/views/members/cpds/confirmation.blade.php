<form method="POST" action="{{ url('attend_cpd') }}" enctype="multipart/form-data">
	@csrf
	<input type="hidden" name="cpd_id" value="{{ $event->id }}">
	<div class="modal-body">
		@if((\Auth::user()?->latestMembership?->expiry_date >= date('Y-m-d')))
			@if($event?->member_rate > 0)
				This CPD is will cost you {{ ($event->member_rate) }}<br>
				Payment Instructions
			@else
				CPD Instructions
			@endif
		@else
			@if($event?->rate > 0)
				This CPD is will cost you {{ ($event?->member_rate) }}<br>
				Payment Instructions
			@else
				CPD Instructions
			@endif
		@endif
		<img src="{{ asset('assets/images/payments.jpeg') }}" width="100%" />
		<label class="mt-4">Payment Proof</label>
		<input type="file" name="payment_proof" class="form-control">
	</div>
	<div class="modal-footer text-end">
		<button type="submit" class="btn btn-primary">Confirm Attendence</button>
	</div>
</form>