<?php

namespace App\Jobs;

use App\Models\Attendence;
use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use ZipArchive;
use App\Mail\BulkDownloadComplete;
use Illuminate\Support\Str;


class DownloadBulkCertificatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $eventId;
    protected $loggedInUser;

    public function __construct($eventId, $loggedInUser)
    {
        $this->eventId = $eventId;
        $this->loggedInUser = $loggedInUser;
    }

    public function handle()
    {
        $event = Event::find($this->eventId);
        if (!$event) {
            Log::error('Event not found with ID: ' . $this->eventId);
            return;
        }

        $userIds = Attendence::where('event_id', $this->eventId)
            ->where('status', 'Attended')
            ->pluck('user_id')->toArray();

        $zipFileName = 'bulk_events_certificates_' . $this->eventId . '.zip';
        $zipFilePath = public_path('certificates/' . $zipFileName);

        if (!File::exists(public_path('certificates'))) {
            File::makeDirectory(public_path('certificates'), 0777, true, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            Log::error('Could not create ZIP file: ' . $zipFilePath);
            return;
        }

        $certificatePaths = [];

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                try {
                    $fileName = 'certificate_generated_' . $user->id . '.png';
                    $filePath = public_path('certificates/' . $fileName);

                    $this->generateCertificate($this->eventId, $userId, $filePath);

                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, $user->name . '_certificate.png');
                        $certificatePaths[] = $filePath;
                    } else {
                        Log::error('Certificate file not found for user: ' . $user->name);
                    }
                } catch (\Exception $e) {
                    Log::error('Error generating certificate for user: ' . $user->name . ' - ' . $e->getMessage());
                }
            }
        }

        $zip->close();

        foreach ($certificatePaths as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }
        \Mail::to($this->loggedInUser->email)->send(new BulkDownloadComplete($this->eventId, $this->loggedInUser, $zipFileName));
        Log::info('Bulk certificates download job completed for event ID: ' . $this->eventId);
    }

    protected function generateCertificate($event_id, $user_id, $filePath)
    {
        try {
            $manager = new ImageManager(new Driver());
            $event = Event::find($event_id);
            $user = User::find($user_id);
            $name = $user->name;
            $membership_number = $user->membership_number;

            $templatePath = public_path('images/event_annual_certificate.jpeg');
            if (!file_exists($templatePath)) {
                throw new \Exception('Certificate template not found.');
            }

            $image = $manager->read($templatePath);

            if ($event->event_type == 'Annual') {
                $this->customizeAnnualCertificate($image, $event, $name, $membership_number);
            } else {
                $this->customizeRegularCertificate($image, $event, $name, $membership_number);
            }

            $image->save($filePath);

        } catch (\Exception $e) {
            Log::error('Error generating certificate: ' . $e->getMessage());
            throw $e;
        }
    }

    // Helper method for customizing an annual event certificate
    private function customizeAnnualCertificate($image, $event, $name, $membership_number)
    {
        // Additional information for Annual events
        $place = $event->place ?? 'Not specified';
        $theme = $event->theme ?? 'Not specified';
        $annual_event_date = Carbon::parse($event->annual_event_date)->format('jS F Y');
        $organizing_committee = $event->organizing_committee ?? 'Institute of Procurement Professionals of Uganda (IPPU)';

        // Name Placement
        $image->text(Str::title($name), 800, 500, function ($font) {
            // $font->file(public_path('fonts/Roboto-Bold.ttf'));
            $font->filename(public_path('fonts/POPPINS-BOLD.TTF'));
            $font->color('#b01735'); // Dark red color
            $font->size(50); // Increased size for better visibility
            $font->align('center');
            $font->valign('middle');
        });



        // Event Name Placement
        $image->text($event->name, 800, 620, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#008000'); // Green color
            $font->size(30); // Increased size
            $font->align('center');
            $font->valign('middle');
        });

        // Theme Text Placement
        // Theme Text Placement (split into two lines)
        $theme = wordwrap('THEME: ' . $theme, 50, "\n", true); // Adjust the 50 to fit the length you want per line

        $image->text($theme, 800, 680, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189'); // Blue color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });


        // Organizer and Date Text Placement
        $image->text('Organised by ' . $organizing_committee, 800, 740, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#405189'); // Blue color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        // Date and Place Text Placement
        $image->text('on ' . $annual_event_date . ' at ' . $place . '.', 800, 780, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#405189'); // Blue color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        // CPD Points Text Placement
        $image->text('This activity was awarded ' . $event->points . ' CPD Credit Points (Hours) of IPPU', 800, 830, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#008000'); // Green color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        // convert the image to png
        $image->toPng();
    }

    private function customizeRegularCertificate($image, $event, $name, $membership_number)
    {
        // Additional information for Annual events
        $place = $event->location ?? 'Not specified';
        $theme = $event->theme ?? 'Not specified';
        $annual_event_date = Carbon::parse($event->annual_event_date)->format('jS F Y');
        $organizing_committee = $event->organizing_committee ?? 'Institute of Procurement Professionals of Uganda (IPPU)';

        // Name Placement
        $image->text(Str::title($name), 800, 500, function ($font) {
            // $font->file(public_path('fonts/Roboto-Bold.ttf'));
            $font->filename(public_path('fonts/POPPINS-BOLD.TTF'));
            $font->color('#b01735'); // Dark red color
            $font->size(50); // Increased size for better visibility
            $font->align('center');
            $font->valign('middle');
        });



        // Event Name Placement
        $image->text($event->name, 800, 620, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#008000'); // Green color
            $font->size(30); // Increased size
            $font->align('center');
            $font->valign('middle');
            $font->wrap(1500);
        });

        // Theme Text Placement
        // Theme Text Placement (split into two lines)
        // $theme = wordwrap('THEME: ' . $theme, 50, "\n", true); // Adjust the 50 to fit the length you want per line

        // $image->text($theme, 800, 680, function ($font) {
        //     $font->filename(public_path('fonts/Roboto-Bold.ttf'));
        //     $font->color('#405189'); // Blue color
        //     $font->size(30);
        //     $font->align('center');
        //     $font->valign('middle');
        // });


        // Organizer and Date Text Placement
        $image->text('Organised by ' . $organizing_committee, 800, 700, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#405189'); // Blue color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);
        
        if ($startDate->isSameDay($endDate)) {
            $x = 700;
            $formattedRange = $startDate->format('jS F Y');
        } elseif ($startDate->month === $endDate->month) {
            $x = 720;
            $formattedRange = $startDate->format('jS') . ' - ' . $endDate->format('jS F Y');
        } else {
            $x = 780;
            $formattedRange = $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');
        }
        
        // Date and Place Text Placement
        $image->text('on ' . $formattedRange, 800, 780, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#405189'); // Blue color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        // CPD Points Text Placement
        $image->text('This activity was awarded ' . $event->points . ' CPD Credit Points (Hours) of IPPU', 800, 830, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#008000'); // Green color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        // convert the image to png
        $image->toPng();
    }
}