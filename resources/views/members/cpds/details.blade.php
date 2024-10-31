@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-8 col-lg-8 col-xl-8">
		<div class="card">
			<div class="card-header">
				<h5 class="card-title">{{ $event->topic }}</h5>
			</div>
			<div class="card-body">
				<div>
					<h6>Description</h6>
					{!! $event->content !!}
				</div>
				<div>
					<h6>Target Group</h6>
					{!! $event->target_group !!}
				</div>
			</div>
			<div class="card-footer bg-light">
				<div class="flex-row d-flex align-items-center justify-content-between">
					<div>
						<h6 class="text-danger font-weight-bold fw-medium">Start Date</h6>
						<span>{{ date('F j, Y, g:i a',strtotime($event->start_date)) }}</span>
					</div>
					<div>
						<h6 class="text-danger font-weight-bold fw-medium">End Date</h6>
						<span>{{ date('F j, Y, g:i a',strtotime($event->end_date)) }}</span>
					</div>
					<div>
						<h6 class="text-warning font-weight-bold fw-medium">Rate</h6>
						<span>{{ ($event->member_rate) ? number_format($event->member_rate) : 'Free'}}</span>
					</div>
					<div>
						<h6 class="text-warning font-weight-bold fw-medium">Location</h6>
						<span>{{ $event->location}}</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-4 col-xl-4">
	<!-- Simple card -->
	<div class="">
		<img class="card-img-top img-fluid image" src="{{ asset('storage/banners/'.$event->banner) }}" alt="{{ $event->name }}" onerror="this.onerror=null;this.src='https://ippu.or.ug/wp-content/uploads/2020/08/ppulogo.png';">
		<div class="card-body">
			<div class="mt-2 text-end">
				@if((($event->start_date >= date('Y-m-d')) || ($event->end_date <= date('Y-m-d'))) && is_null($event->attended))
				<a data-id="{{ $event->id }}" data-rate="{{ $event->normal_rate ?? 0 }}" title="{{__('Attend CPD')}}" class="btn btn-primary book-btn">Book</a>
				@elseif(!is_null($event->attended))
				{{-- <span href="javascript:void(0)" class="btn btn-light btn-sm">
					Attended
				</span> --}}
				@if($event->attended->status != "Pending")
					<a href="{{ url('cpd_certificate/'.$event->id) }}" class="btn btn-primary btn-sm" target="_blank">Certificate</a>
					<a href="{{ asset('storage/attachments/'.$event->resource_name) }}" class="btn btn-warning btn-sm" download>Download Resource</a>
				@endif
				@endif
			</div>
		</div>
	</div><!-- end card -->
</div>
</div>
@endsection


@section('customjs')

<script>
$(document).on('click', '.book-btn', function(event) {
    event.preventDefault();

    const cpdId = $(this).data('id');
    const cpdRate = $(this).data('rate') || 0;

    const cashUrl = "{{ url('redirect_url_cpds') }}?cpd_id=" + cpdId;
    const cashlessUrl = "{{ url('attend_cpd') }}/" + cpdId;

    Swal.fire({
        title: 'Select Booking Type',
        html: `
            <div class="mb-2 form-check">
                <input class="form-check-input" type="radio" name="bookingType" id="cashless" value="cashless" checked>
                <label class="form-check-label" for="cashless">Cashless</label>
            </div>
            <div class="mb-2 form-check">
                <input class="form-check-input" type="radio" name="bookingType" id="cash" value="cash">
                <label class="form-check-label" for="cash">Cash</label>
            </div>
            <div id="cashlessAmountContainer" class="mb-3">
                <label for="cashlessAmount" class="form-label">Amount</label>
                <input type="number" id="cashlessAmount" class="form-control" value="${cpdRate}" min="1">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Proceed with Booking',
        confirmButtonColor: '#3085d6',
        preConfirm: function() {
            const bookingType = $('input[name="bookingType"]:checked').val();
            let bookingData = { type: bookingType };

            if (bookingType === 'cashless') {
                const amount = $('#cashlessAmount').val();
                bookingData.amount = amount;
            }

            return bookingData;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const bookingType = result.value.type;
            const amount = result.value.amount || cpdRate;

            // Select the appropriate URL based on booking type
            const bookingUrl = (bookingType === 'cash') ? cashUrl : cashlessUrl;

            // Show loading spinner
            Swal.fire({
                title: 'Processing Booking...',
                text: 'Please wait while we process your booking.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send booking data via AJAX or redirect as needed
            $.ajax({
                url: bookingUrl,
                method: 'GET',
                data: {
                    type: bookingType,
                    amount: amount,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response)
                    Swal.fire('Booked!', 'Your booking was successful.', 'success');
                    if(bookingType === 'cashless') {
                         //redirect to the payment page
                        window.location.href = response.data;
                    }
                    else{
                        //reload the page
                        location.reload();
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'There was an error with your booking. Please try again.', 'error');
                }
            });
        }
    });

    // Toggle amount input for cashless option
    $(document).on('change', 'input[name="bookingType"]', function() {
        const bookingType = $('input[name="bookingType"]:checked').val();
        $('#cashlessAmountContainer').toggle(bookingType === 'cashless');
    });
});

</script>

@endsection