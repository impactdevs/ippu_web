<?php

namespace App\Jobs;

use App\Mail\EventCertificate;
use App\Models\Attendence;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class SendBulkEmailEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $eventId;

    /**
     * Create a new job instance.
     *
     * @param  int  $eventId
     * @return void
     */
    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $event = Event::find($this->eventId);
        if (!$event) {
            Log::error('Event not found with ID: ' . $this->eventId);
            return;
        }

        // Get all users who attended the event
        $userIds = Attendence::where('event_id', $this->eventId)
            ->where('status', 'Attended')
            ->pluck('user_id')->toArray();

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                // Generate and email certificate
                $this->emailCertificate($this->eventId, $userId);
            }
        }

        Log::info('Certificates have been emailed successfully for event ID: ' . $this->eventId);
    }

    /**
     * Generate and email the certificate.
     *
     * @param  int  $eventId
     * @param  int  $userId
     * @return void
     */
    public function emailCertificate($event_id, $user_id)
    {
        try {
            $manager = new ImageManager(new Driver());
            $event = Event::find($event_id);
            $user = \App\Models\User::find($user_id);
            $name = $user->name;
            $membership_number = $user->membership_number;
            $id = $user->id;
    
    
    
            $formattedRange = Carbon::parse($event->start_date)->format('jS F Y') . ' - ' . Carbon::parse($event->end_date)->format('jS F Y');
    
            // Determine the template based on event type
            $templatePath = $event->event_type == 'Annual' ? public_path('images/event_annual_certificate.jpeg') : public_path('images/certificate-template.jpeg');
            if (!file_exists($templatePath)) {
                throw new \Exception('Certificate template not found.');
            }
    
            $image = $manager->read($templatePath);
    
            // Customize certificate details based on event type
            if ($event->event_type == 'Annual') {
                $this->customizeAnnualCertificate($image, $event, $name, $membership_number);
            } else {
                $this->customizeRegularCertificate($image, $event, $name, $membership_number);
            }
    
            $file_name = 'certificate-generated_' . $id . '.png';
            $image->save(public_path('images/' . $file_name));
    
            // Send the certificate via email
            $path = public_path('images/' . $file_name);
            Mail::to($user->email)->send(new EventCertificate($name, $event, $path, $formattedRange));
    
            // Optionally, delete the certificate after sending the email
            unlink($path);
    
            return redirect()->back()->with('success', 'Certificate generated and emailed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while generating the certificate: ' . $e->getMessage());
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
    $image->text($name, 800, 500, function ($font) {
        // $font->filename(public_path('fonts/Roboto-Bold.ttf'));
        $font->file(public_path('fonts/Roboto-Bold.ttf'));
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

    // Membership Number Placement
    // $image->text(($membership_number ?? 'N/A'), 1900, 2000, function ($font) {
    //     $font->filename(public_path('fonts/Roboto-Bold.ttf'));
    //     $font->color('#405189'); // Blue color
    //     $font->size(45);
    //     $font->align('center');
    //     $font->valign('middle');
    // });
}

// Helper method for customizing a regular event certificate
private function customizeRegularCertificate($image, $event, $name, $membership_number)
{

   $image->text('PRESENTED TO', 420, 250, function ($font) {
       $font->filename(public_path('fonts/Roboto-Bold.ttf'));
       $font->color('#405189');
       $font->size(12);
       $font->align('center');
       $font->valign('middle');
   });

   //dd($user->name);

   $image->text($name, 420, 300, function ($font) {
       $font->filename(public_path('fonts/Roboto-Bold.ttf'));
       $font->color('#b01735');
       $font->size(20);
       $font->align('center');
       $font->valign('middle');
       $font->lineHeight(1.6);
   });



   $image->text('FOR ATTENDING THE', 420, 340, function ($font) {
       $font->filename(public_path('fonts/Roboto-Bold.ttf'));
       $font->color('#405189');
       $font->size(12);
       $font->align('center');
       $font->valign('middle');
       $font->lineHeight(1.6);
   });

   //add event name
   $image->text($event->name, 420, 370, function ($font) {
       $font->filename(public_path('fonts/Roboto-Regular.ttf'));
       $font->color('#008000');
       $font->size(22);
       $font->align('center');
       $font->valign('middle');
       $font->lineHeight(1.6);
   });



   $startDate = Carbon::parse($event->start_date);
   $endDate = Carbon::parse($event->end_date);

   if ($startDate->month === $endDate->month) {
       $x = 420;
       // Dates are in the same month
       $formattedRange = $startDate->format('jS') . ' - ' . $endDate->format('jS F Y');
   } else {
       $x = 480;
       // Dates are in different months
       $formattedRange = $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');
   }


   $image->text('Organized by the Institute of Procurement Professionals of Uganda on ' . $formattedRange, $x, 400, function ($font) {
       $font->filename(public_path('fonts/Roboto-Regular.ttf'));
       $font->color('#405189');
       $font->size(12);
       $font->align('center');
       $font->valign('middle');
       $font->lineHeight(1.6);
   });

   //add membership number
   $image->text('MembershipNumber: ' . $membership_number ?? "N/A", 450, 483, function ($font) {
       $font->filename(public_path('fonts/Roboto-Bold.ttf'));
       $font->color('#405189');
       $font->size(12);
       $font->align('center');
       $font->valign('middle');
       $font->lineHeight(1.6);
   });

}
}
