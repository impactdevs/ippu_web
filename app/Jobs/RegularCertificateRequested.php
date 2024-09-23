<?php

namespace App\Jobs;

use App\Events\CertificateGenerated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;
use Carbon\Carbon;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class RegularCertificateRequested implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;


    public $name;

    public $membership_number;

    public $id;


    /**
     * Create a new job instance.
     */
    public function __construct(
        Event $event,
        string $name,
        string $membership_number,
        $id
    ) {
        $this->event = $event;
        $this->name = $name;
        $this->membership_number = $membership_number;
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



        $image->text('PRESENTED TO', 420, 250, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(12);
            $font->align('center');
            $font->valign('middle');
        });

        //dd($user->name);

        $image->text($this->name, 420, 300, function ($font) {
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
        $image->text($this->event->name, 420, 370, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#008000');
            $font->size(22);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });



        $startDate = Carbon::parse($this->event->start_date);
        $endDate = Carbon::parse($this->event->end_date);

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
        $image->text('MembershipNumber: ' . $this->membership_number ?? "N/A", 450, 483, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(12);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $file_name = 'certificate-generated_' . $this->id . '.png';
        $image->save(public_path('images/' . $file_name));

        // throw an event to broadcast the certificate
        event(new CertificateGenerated($file_name));
    }
}
