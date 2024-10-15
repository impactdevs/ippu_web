<form method="POST" action="{{ url('attend_event') }}">
	@csrf
	<input type="hidden" name="event_id" value="{{ $event->id }}">
	<div class="modal-body">
		@if((\Auth::user()?->latestMembership?->expiry_date >= date('Y-m-d')))
			@if($event->member_rate > 0)
				This event is will cost you {{ number_format($event->member_rate) }}<br>
				Payment Instructions
			@else
				Event Instructions
			@endif
		@else
			@if($event->rate > 0)
				This event is will cost you {{ ($event?->member_rate) }}<br>
				Payment Instructions
			@else
				Event Instructions
			@endif
		@endif
		<img src="{{ asset('assets/images/payments.jpeg') }}" width="100%" />
	</div>
	<div class="modal-footer text-end">
		<button type="submit" class="btn btn-primary">Confirm Attendence</button>
	</div>
</form>