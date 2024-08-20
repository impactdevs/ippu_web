@extends('layouts.app')
@section('content')
<div class="text-end mb-3">
	@if(\Auth::user()->user_type == "Admin")
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-newsletter">
        Add Newsletter
      </button>
      @endif
</div>
  <div class="row">
	@foreach($communications as $communication)
	<div class=" col-md-6">
        <div class="card">
            <h5 class="card-header">{{$communication->title}}</h5>
            <div class="card-body">
              <h5 class="card-title">{{$communication->sub_title}}</h5>
              <p class="card-text">
                {{$communication->description}}
              </p>
				<a href="{{ url('/admin/newsletter/'.$communication->id) }}" class="btn btn-primary">Newsletter Details</a>
                 @if(\Auth::user()->user_type == "Admin")
                {{-- send newsletter --}}
                <form action="{{ url('/admin/send_newsletter/' . $communication->id) }}" method="POST" style="display: inline;"
                    class="m-0 p-0">
                    @csrf
                    <button type="submit" class="btn btn-success">Send Newsletter</button>
                </form>
                @endif
            </div>
          </div>
	</div>
	@endforeach
</div>

<div class="modal" tabindex="-1" id="add-newsletter">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Newsletter</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="card" method="POST" id="newsletterForm">
            @csrf
            <div class="card-body row">
                <div class="form-group mb-3">
                    <label>Title</label>
                    <input type="text" class="form-control" name="title">
                </div>

                <div class="form-group mb-3">
                    <label>Sub-title</label>
                    <input type="text" class="form-control" name="sub_title">
                </div>

                <div class="form-group mb-3">
                    <label>Message</label>
                    <textarea class="form-control" id="message" maxlength="200" name="description"></textarea>
                    <div id="charCount">0 / 200</div>
                </div>

                <div class="form-group mb-3">
                    <label>Newsletter File(.pdf format)</label>
                    <input type="file" class="form-control" name="newsletter_file">
                </div>

            </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="saveNewsletterBtn">Save</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('customjs')
 <script>
$(document).ready(function() {
    // Add a click event handler to the "Save" button
    $('#saveNewsletterBtn').on('click', function() {
        console.log('making a request');

        // Create a FormData object to handle form fields and files
        var formData = new FormData($('#newsletterForm')[0]);

        // Make a POST request to the server
        $.ajax({
            url: '/admin/newsletter',
            method: 'POST',
            data: formData,
            processData: false,  // Important: prevent jQuery from processing the data
            contentType: false,  // Important: let the server handle the content type
            success: function(response) {
                console.log("response:", response);
                // If the request is successful, hide the modal
                $('#staticBackdrop').modal('hide');
                // Reload the page
                location.reload();
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
});

    </script>
@endsection
