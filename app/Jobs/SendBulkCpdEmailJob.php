<?php

namespace App\Jobs;

use App\Mail\CertificateMail;
use App\Models\Attendence;
use App\Models\Cpd;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class SendBulkCpdEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cpdId;

    /**
     * Create a new job instance.
     *
     * @param  int  $cpdId
     * @return void
     */
    public function __construct($cpdId)
    {
        $this->cpdId = $cpdId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cpd = Cpd::find($this->cpdId);
        if (!$cpd) {
            Log::error('CPD not found with ID: ' . $this->cpdId);
            return;
        }

        // Get all users who attended the CPD event
        $userIds = Attendence::where('cpd_id', $cpd->id)
            ->where('status', 'Attended')
            ->pluck('user_id')->toArray();

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                // Generate and email certificate
                $this->emailCertificate($this->cpdId, $userId);
            }
        }

        Log::info('CPD certificates have been emailed successfully for CPD ID: ' . $this->cpdId);
    }

    /**
     * Generate and email the certificate.
     *
     * @param  int  $cpdId
     * @param  int  $userId
     * @return void
     */
    public function emailCertificate($cpd_id, $user_id)
    {
        try {

            $manager = new ImageManager(new Driver());

            $event = Cpd::find($cpd_id);
            // dd($event);
            $user = \App\Models\User::find($user_id);

            // Load the certificate template
            $image = $manager->read(public_path('images/cpd-certificate-template.jpg'));

            // Add details to the certificate
            $image->text($event->code, 173, 27, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($user->name, 780, 550, function ($font) {
                $font->file(public_path('fonts/GreatVibes-Regular.ttf'));
                $font->size(45);
                $font->color('#1F45FC');
                $font->align('center');
            });

            $image->text($event->topic, 730, 690, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $startDate = Carbon::parse($event->start_date);
            $endDate = Carbon::parse($event->end_date);

            $x = ($startDate->month === $endDate->month) ? 720 : 780;
            $formattedRange = ($startDate->month === $endDate->month)
                ? $startDate->format('jS') . ' - ' . $endDate->format('jS F Y')
                : $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');

            $image->text('on ', 600, 760, function ($font) {
                $font->file(public_path('fonts/Roboto-Regular.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($formattedRange, $x, 760, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($event->hours . " CPD HOURS", 1400, 945, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(17);
                $font->color('#000000');
                $font->align('center');
            });



            // Save the certificate to a temporary file
            $path = public_path('certificates/' . $user->id . '_certificate.png');
            $image->save($path);

            // Send the certificate via email
            // Mail::to($user->email)->send(new CertificateMail($user, $event, $path));
            // Send the certificate via email
            Mail::to($user->email)->send(new CertificateMail($user, $event, $path, $formattedRange));


            // Optionally, delete the file after sending the email
            unlink($path);

            return redirect()->back()->with('success', 'Certificate has been emailed successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while emailing the certificate: ' . $e->getMessage());
        }
    }
}
