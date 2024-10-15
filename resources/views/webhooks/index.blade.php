@extends('layouts.app')
@section('breadcrumb')
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Members</h4>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Transactions</li>
            </ol>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        {{-- <div class="card-header"></div> --}}
        <div class="card-body">
            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover" id="webhooks">
                    <thead>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Transaction ID</th>
                        <th>Currency</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        @foreach ($webhooks as $webhook)
                            <tr>
                                <td>{{ $webhook->customer_name }}</td>
                                <td>{{ $webhook->customer_email }}</td>
                                <td>{{ $webhook->transaction_id }}</td>
                                <th>{{ $webhook->currency }}</th>
                                <td>{{ $webhook->amount }}</td>
                                <th>{{ $webhook->status }}</th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('customjs')
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#webhooks').DataTable({
                layout: {
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                }
            });
        });
    </script>
@endsection
