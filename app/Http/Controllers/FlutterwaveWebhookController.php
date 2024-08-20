<?php

namespace App\Http\Controllers;

use App\Models\FlutterwaveWebhook;
use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FlutterwaveWebhookController extends Controller
{
        public function index()
    {
        $webhooks = FlutterwaveWebhook::latest()->get();
        return view('webhooks.index', compact('webhooks'));
    }
    
    public function handleWebhook(Request $request)
    {
        // Log the payload for debugging purposes
        // Log the payload for debugging purposes
        WebhookLog::create([
            'event_type' => $request->input('event'),
            'payload' => json_encode($request->all()),
        ]);

        // // Verify the signature
        // $signature = $request->header('verif-hash');
        // $secret = env('FLUTTERWAVE_SECRET_HASH');

        // if ($signature !== $secret) {
        //     return response()->json(['message' => 'Invalid signature'], 403);
        // }

        // Handle the webhook event
        $event = $request->input('event');
        switch ($event) {
            case 'charge.completed':
                $this->handleChargeCompleted($request->input('data'));
                break;
            // Handle other events here
        }

        return response()->json(['status' => 'success'], 200);
    }

    protected function handleChargeCompleted($data)
    {
        // Check if the transaction already exists
        $transaction = FlutterwaveWebhook::where('transaction_id', $data['id'])->first();

        if (!$transaction) {
            // Create a new transaction record
            FlutterwaveWebhook::create([
                'transaction_id' => $data['id'],
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'status' => $data['status'],
                'customer_email' => $data['customer']['email'],
                'customer_name' => $data['customer']['name'] ?? null,
                'metadata' => $data['meta'] ?? null,
            ]);

            Log::info('Charge Completed:', $data);
        }
    }
}
