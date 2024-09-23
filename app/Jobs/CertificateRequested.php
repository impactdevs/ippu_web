<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;
use Carbon\Carbon;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CertificateRequested implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;


    public $name;

    public $id;

    /**
     * Create a new job instance.
     */
    public function __construct(
        Event $event,
        string $name,
        $id
    ) {
        $this->event = $event;
        $this->name = $name;
        $this->id = $id;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $manager = new ImageManager(driver: new Driver());
        // Determine the template based on event type
        $templatePath = $this->event->event_type == 'Annual' ? public_path('images/event_annual_certificate.jpeg') : public_path('images/certificate-template.jpeg');
        if (!file_exists($templatePath)) {
            throw new \Exception('Certificate template not found.');
        }
        $image = $manager->read($templatePath);
        // Additional information for Annual events
        $place = $this->event->place ?? 'Not specified';
        $theme = $this->event->theme ?? 'Not specified';
        $annual_event_date = Carbon::parse($this->event->annual_event_date)->format('jS F Y');
        $organizing_committee = $this->event->organizing_committee ?? 'Institute of Procurement Professionals of Uganda (IPPU)';

        // Name Placement
        $image->text($this->name, 800, 500, function ($font) {
            // $font->file(public_path('fonts/Roboto-Bold.ttf'));
            $font->filename(public_path('fonts/GreatVibes-Regular.ttf'));
            $font->color('#b01735'); // Dark red color
            $font->size(50); // Increased size for better visibility
            $font->align('center');
            $font->valign('middle');
        });



        // Event Name Placement
        $image->text($this->event->name, 800, 620, function ($font) {
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
        $image->text('This activity was awarded ' . $this->event->points . ' CPD Credit Points (Hours) of IPPU', 800, 830, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#008000'); // Green color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        $file_name = 'certificate-generated_' . $this->id . '.png';
        $image->save(public_path('images/' . $file_name));
    }
}
