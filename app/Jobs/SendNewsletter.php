<?php

namespace App\Jobs;

use App\Models\Newsletter;
use App\Models\User;
use App\Mail\Newsletter as NewsletterMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsletter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $newsletter;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Newsletter $newsletter
     */
    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all verified users
        $users = User::whereNotNull('email_verified_at')->get();

        // Extract the email addresses
        $emails = $users->pluck('email')->toArray();

        // Send the email with BCC
        Mail::to('nsengiyumvawilberforce@gmail.com')
            ->bcc($emails)
            ->send(new NewsletterMail(
                $this->newsletter->title,
                $this->newsletter->newsletter_file_url,
                $this->newsletter->description
            ));
    }
}
