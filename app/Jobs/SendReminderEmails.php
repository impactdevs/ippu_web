<?php

namespace App\Jobs;

use App\Mail\RemainderEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendReminderEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $users = [];
            if ($this->request->type == "cpd") {
                $users = User::whereHas('cpd_attendences', function($query) {
                    $query->where('status', $this->request->status);
                })->get();
            } else {
                $users = User::whereHas('event_attendences', function($query) {
                    $query->where('status', $this->request->status);
                })->get();
            }

            foreach ($users as $user) {
                Mail::to($user)->send(new RemainderEmail($this->request, $user));
            }
        } catch (\Exception $e) {
            // Handle the exception
            \Log::error('Failed to send reminder emails: ' . $e->getMessage());
            // Optionally, rethrow or handle the exception as needed
        }
    }
}
