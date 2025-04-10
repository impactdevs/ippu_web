<?php

namespace App\Jobs;

use App\Models\Attendence;
use App\Models\Cpd;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use App\Mail\BulkDownloadComplete;
use Illuminate\Support\Str;

class DownloadBulkCPDCertificatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cpdId;
    protected $loggedInUser;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cpdId, $loggedInUser)
    {
        $this->cpdId = $cpdId;
        $this->loggedInUser = $loggedInUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('Bulk CPD certificates download job started for CPD ID: ' . $this->cpdId);
        $cpd = Cpd::find($this->cpdId);
        if (!$cpd) {
            Log::error('CPD event not found with ID: ' . $this->cpdId);
            return;
        }

        // Get all users who attended the CPD
        $userIds = Attendence::where('cpd_id', $this->cpdId)
            ->where('status', 'Attended')
            ->pluck('user_id')->toArray();

        // Create a unique name for the ZIP file
        $zipFileName = 'bulk_certificates_' . $this->cpdId . '.zip';
        $zipFilePath = public_path('certificates/' . $zipFileName);

        // Ensure the directory for storing certificates exists
        if (!File::exists(public_path('certificates'))) {
            File::makeDirectory(public_path('certificates'), 0777, true, true);
        }

        // Create a new ZIP file
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            Log::error('Could not create ZIP file: ' . $zipFilePath);
            return;
        }

        $certificatePaths = [];

        // Generate a certificate for each user and add it to the ZIP
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                try {
                    // Generate the certificate for the user
                    $this->downloadCertificate($this->cpdId, $userId);

                    // Path to the generated certificate
                    $certificatePath = public_path('certificates/' . $user->id . '_certificate.png');

                    // Add the certificate to the ZIP file
                    if (file_exists($certificatePath)) {
                        $zip->addFile($certificatePath, $user->name . '_certificate.png');
                        $certificatePaths[] = $certificatePath; // Track the path for cleanup
                    } else {
                        Log::error('Certificate file not found for user: ' . $user->name);
                    }
                } catch (\Exception $e) {
                    Log::error('Error generating certificate for user: ' . $user->name . ' - ' . $e->getMessage());
                }
            }
        }

        $zip->close();

        // Cleanup: Delete the individual certificate files after they are added to the ZIP
        foreach ($certificatePaths as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }

        \Mail::to($this->loggedInUser->email)->send(new BulkDownloadComplete($this->cpdId, $this->loggedInUser, $zipFileName));


        // Log successful completion
        // Log::info('Bulk CPD certificates download job completed for CPD ID: ' . $this->cpdId);
    }

    public function downloadCertificate($cpd_id, $user_id)
    {
        //dd("am here");
        try {
            $manager = new ImageManager(new Driver());

            $event = Cpd::find($cpd_id);
            $user = \App\Models\User::find($user_id);

            // Load the certificate template
            $image = $manager->read(public_path('images/final-cpd-template.jpeg'));

            // Add details to the certificate
            $image->text($event->code, 180, 85, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });
            $image->text(Str::title($user->name), 780, 625, function ($font) {
                $font->file(public_path('fonts/POPPINS-BOLD.TTF'));
                $font->size(45);
                $font->color('#1F45FC');
                $font->align('center');
            });

            $image->text(Str::title($event->topic), 550, 770, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(25);
                $font->color('#000000');
                $font->align('left');
                $font->valign('middle');
                $font->lineHeight(2.0);
                $font->wrap(1000);
            });

            $image->text(Str::title(Str::lower($user->name)), 780, 625, function ($font) {
                $font->file(public_path('fonts/POPPINS-BOLD.TTF'));
                $font->size(45);
                $font->color('#1F45FC');
                $font->align('center');
            });

            $startDate = Carbon::parse($event->start_date);
            $endDate = Carbon::parse($event->end_date);

            $x = ($startDate->month === $endDate->month) ? 720 : 780;
            if ($startDate->isSameDay($endDate)) {
                $formattedRange = $startDate->format('jS F Y');
            } elseif ($startDate->month === $endDate->month && $startDate->year === $endDate->year) {
                $formattedRange = $startDate->format('jS') . ' - ' . $endDate->format('jS F Y');
            } else {
                $formattedRange = $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');
            }
            
            $image->text($formattedRange, $x, 825, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($event->hours . " CPD HOURS", 1400, 1020, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(17);
                $font->color('#000000');
                $font->align('center');
            });

            // Save the certificate to a temporary file
            $path = public_path('certificates/' . $user->id . '_certificate.png');
            $image->save($path);

            // Return the certificate for download
            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Handle any errors that occur during the certificate generation
            return redirect()->back()->with('error', 'An error occurred while generating the certificate: ' . $e->getMessage());
        }
    }
}
