<?php

namespace App\Jobs;

use App\Events\CertificateGenerated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CpdCertificateGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;

    public $user;



    /**
     * Create a new job instance.
     */
    public function __construct(
        $event, $user
    ) {
        $this->event = $event;
        $this->user = $user;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $manager = new ImageManager(new Driver());
        //read the image from the public folder
        $image = $manager->read(public_path('images/cpd-certificate-template.jpg'));

        $eventFound = $this->event;

        $image->text($eventFound->code, 173, 27, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#000000');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text($this->user->name, 780, 550, function ($font) {
            $font->filename(public_path('fonts/GreatVibes-Regular.ttf'));
            $font->color('#1F45FC');
            $font->size(45);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text('Attended a Continuing Professional Development(CPD) activity', 760, 620, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#000000');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        //add event name
        $image->text('"' . $eventFound->topic . '"', 730, 690, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#000000');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });


        $startDate = Carbon::parse($eventFound->start_date);
        $endDate = Carbon::parse($eventFound->end_date);

        if ($startDate->month === $endDate->month) {
            $x = 720;
            // Dates are in the same month
            $formattedRange = $startDate->format('jS') . ' - ' . $endDate->format('jS F Y');
        } else {
            $x = 780;
            // Dates are in different months
            $formattedRange = $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');
        }


        $image->text('on ', 600, 760, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#000000');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text($formattedRange, $x, 760, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#000000');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text($eventFound->hours . "CPD HOURS", 1400, 945, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#000000');
            $font->size(17);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->toPng();

        //let file name be cpd-certificate-generated_user_id.png
        $file_name = 'cpd-certificate-generated_' . $this->user->id . '.png';

        //save the image to the public folder
        $image->save(public_path('images/' . $file_name));

        event(new CertificateGenerated($file_name));

    }
}
