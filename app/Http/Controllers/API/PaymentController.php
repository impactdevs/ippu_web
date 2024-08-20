<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function flutterWavePayment()
    {
        $payments = DB::table('flutterwave_webhooks')->where('customer_email', auth()->user()->email)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Payment fetched successfully',
            'data' => $payments
        ], 200);
    }


}
