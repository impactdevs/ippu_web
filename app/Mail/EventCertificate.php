<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventCertificate extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $event;
    public $path;
    public $formattedRange;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $formattedRange
     * @return void
     */
    public function __construct($name, $event, $path, $formattedRange )
    {
        $this->name = $name;
        $this->formattedRange = $formattedRange;
        $this->event = $event;
        $this->path = $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.event-certificate')
                    ->subject('Your Event Certificate')
                    ->with([
                        'name' => $this->name,
                        'formattedRange' => $this->formattedRange,
                    ])
                    ->attach($this->path, [
                        'as' => 'certificate.png',
                        'mime' => 'image/png',
                    ])
                    ;
    }
}
