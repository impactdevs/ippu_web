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
    <div class="col-md-6">
        <div class="card">
            <h5 class="card-header">{{$communication->title}}</h5>
            <div class="card-body">
                <h5 class="card-title">{{$communication->sub_title}}</h5>
                <p class="card-text">{{$communication->description}}</p>
                <a href="{{ url('/admin/newsletter/'.$communication->id) }}" class="btn btn-primary">Newsletter Details</a>
                @if(\Auth::user()->user_type == "Admin")
                {{-- send newsletter --}}
                <form class="send-newsletter-form" data-id="{{ $communication->id }}" method="POST" style="display: inline;" class="m-0 p-0">
                    @csrf
                    <button type="button" class="btn btn-success send-newsletter-btn">Send Newsletter</button>
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
                        <label>Newsletter File (.pdf format)</label>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Handle the Save Newsletter button click
    $('#saveNewsletterBtn').on('click', function(e) {
        e.preventDefault(); // Prevent default form submission

        var formData = new FormData($('#newsletterForm')[0]);

        $.ajax({
            url: '/admin/newsletter',
            method: 'POST',
            data: formData,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                Swal.fire({
                    title: 'Saving...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.close(); // Close the loading alert
                Swal.fire({
                    icon: 'success',
                    title: 'Newsletter Added',
                    text: 'The newsletter has been successfully added!',
                    confirmButtonColor: "#3a57e8"
                }).then(function() {
                    location.reload();
                });
            },
            error: function(error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Add Newsletter',
                    text: 'There was an issue adding the newsletter. Please try again.',
                    confirmButtonColor: "#3a57e8"
                });
            }
        });
    });

    // Handle the Send Newsletter button click
    $(document).on('click', '.send-newsletter-btn', function(e) {
        e.preventDefault(); // Prevent default form submission

        var form = $(this).closest('form');
        var id = form.data('id');

        $.ajax({
            url: '/admin/send_newsletter/' + id,
            method: 'POST',
            data: form.serialize(),
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                Swal.fire({
                    title: 'Sending...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                Swal.close(); // Close the loading alert
                Swal.fire({
                    icon: 'success',
                    title: 'Newsletter Sent',
                    text: 'The newsletter has been successfully sent!',
                    confirmButtonColor: "#3a57e8"
                });
            },
            error: function(error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Send Newsletter',
                    text: 'There was an issue sending the newsletter. Please try again.',
                    confirmButtonColor: "#3a57e8"
                });
            }
        });
    });
});
</script>
@endsection
