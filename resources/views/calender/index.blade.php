@extends('layouts.app')
@section('customcss')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<style>
  .fc-custom-today {
  color: blue; /* Change the color of the "Today" button */
}

.fc-custom-month {
  color: green; /* Change the color of the "Month" button */
}

.fc-custom-week {
  color: red; /* Change the color of the "Week" button */
}

.fc-custom-day {
  color: orange; /* Change the color of the "Day" button */
}

.fc-custom-multimonth {
  color: purple; /* Change the color of the "Multi Month" button */
}

</style>

@endsection
@section('content')
<div class="card">
	<div class="card-body">
		<div id="calendar"></div>
	</div>
</div>
@endsection
@section('customjs')
<script>
	$(document).ready(function() {
		var calendarEl = $('#calendar');

		var calendar = new FullCalendar.Calendar(calendarEl[0], {
			initialView: 'multiMonthYear',
			headerToolbar: {
		      left: 'prev,next today',
		      center: 'title',
		      right: 'multiMonthYear,dayGridMonth,timeGridWeek,timeGridDay'
		    },
		    buttonText: {
		    	multiMonthYear: 'All Year',
			    dayGridMonth: 'Month', 
			    timeGridWeek: 'Week',
			    timeGridDay: 'Day',
			    today: 'Today',
		    },
			events: function(fetchInfo, successCallback, failureCallback) {
				$.ajax({
					url: '{{ url('admin/calender') }}', 
					type: 'POST',
					dataType: 'json',
					success: function(response) {
						var events = response;
						successCallback(events);
					},
					error: function(xhr, status, error) {
						console.error('There was an error fetching events:', error);
						failureCallback(error);
					}
				});
			}
		});

		calendar.render();
	});


</script>
@endsection