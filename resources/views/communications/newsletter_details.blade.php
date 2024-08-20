@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            {{ $newsletter->title }}
        </div>
        <div class="card-body">
            {!! $newsletter->description !!}
        </div>
        <div class="card-footer">
            <a class="btn btn-primary" href="{{ url('/admin/download_newsletter/' . $newsletter->id) }}">Download Newsletter</a>
            @if (Auth::user()->user_type == 'Admin')
            {{-- delete newsletter --}}
            <form action="{{ url('/admin/delete_newsletter/' . $newsletter->id) }}" method="POST" style="display: inline;"
                class="m-0 p-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">@lang('Delete')</button>
            </form>

            {{-- edit newsletter --}}
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit-newsletter">
                Edit Newsletter
            </button>
            @endif
        </div>
    </div>

    {{-- Edit Newsletter Modal --}}
    <div class="modal" tabindex="-1" id="edit-newsletter">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Newsletter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="card" id="updateNewsletterForm">
                    @csrf
                    @method('PUT') {{-- Add the PUT method --}}
                    <div class="card-body row">
                        <div class="form-group mb-3">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" value="{{ $newsletter->title }}">
                        </div>

                        <div class="form-group mb-3">
                            <label>Sub-title</label>
                            <input type="text" class="form-control" name="sub_title"
                                value="{{ $newsletter->sub_title }}">
                        </div>

                        <div class="form-group mb-3">
                            <label>Message</label>
                            <textarea class="form-control" id="message" maxlength="200" name="description">{{ $newsletter->description }}</textarea>
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
                    <button type="button" class="btn btn-primary" id="updateNewsletterBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $(document).ready(function() {
            // Update Newsletter
            $('#updateNewsletterBtn').click(function(e) {
                e.preventDefault();

                var formData = new FormData($('#updateNewsletterForm')[0]); // Use [0] to select the form
                //send a post request to the server
                $.ajax({
                    url: "{{ url('/admin/update_newsletter/' . $newsletter->id) }}",
                    type: 'POST', // Change method to type
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        // Hide the modal manually
                        $('#edit-newsletter').modal('hide');
                        // Reload the current page
                        window.location.reload();
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            });
        });
    </script>
@endsection

