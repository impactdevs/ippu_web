<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $event;
    public $path;
    public $formattedRange;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $event, $path, $formattedRange)
    {
        $this->user = $user;
        $this->event = $event;
        $this->path = $path;
        $this->formattedRange = $formattedRange;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.certificate')
                    ->subject('Your Certificate of Completion')
                    ->attach($this->path, [
                        'as' => 'certificate.png',
                        'mime' => 'image/png',
                    ]);
    }
}
