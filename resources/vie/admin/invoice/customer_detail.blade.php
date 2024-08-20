@if(!empty($customer))
    <div class="row">
        <div class="col-md-6">
            <h6>{{__('Customer Details')}}</h6>
            <div class="bill-to">
                <small>
                    <span>{{$customer['name']}}</span><br>
                    <span>{{$customer['contact']}}</span><br>
                    <span>{{$customer['email']}}</span><br>
                    {{-- <span>{{$customer['shipping_city'] . ' , '.$customer['shipping_state'].' , '.$customer['shipping_country'].'.'}}</span><br>
                    <span>{{$customer['shipping_zip']}}</span> --}}

                </small>
            </div>
        </div>
        <div class="col-md-6">
            <h6>{{__('Bill to')}}</h6>
            <div class="bill-to">
                <small>
                    <span>{{$customer['billing_name']}}</span><br>
                    <span>{{$customer['billing_phone']}}</span><br>
                    <span>{{$customer['billing_address']}}</span><br>
                    <span>{{$customer['billing_city'] . ' , '.$customer['billing_state'].' , '.$customer['billing_country'].'.'}}</span><br>
                    <span>{{$customer['billing_zip']}}</span>

                </small>
            </div>
        </div>
        
        <div class="col-md-12 text-end">
            <a href="#" id="remove" class="text-sm btn btn-danger btn-sm">{{__(' Remove')}}</a>
        </div>
    </div>
@endif
