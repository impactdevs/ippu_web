<?php $__env->startSection('customcss'); ?>
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

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="card">
	<div class="card-body">
		<div id="calendar"></div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('customjs'); ?>
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
					url: '<?php echo e(url('admin/calender')); ?>', 
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/calender/index.blade.php ENDPATH**/ ?>