<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class BulkDownloadComplete extends Mailable
{
    use Queueable, SerializesModels;

    public $cpd_id;
    public $user;
    public $zipFilePath;

    /**
     * Create a new message instance.
     *
     * @param  int  $cpd_id
     * @param  \App\Models\User  $user
     * @param  string  $zipFilePath  The path to the zip file to be attached
     * @return void
     */
    public function __construct($cpd_id, $user, $zipFilePath)
    {
        $this->cpd_id = $cpd_id;
        $this->user = $user;
        $this->zipFilePath = $zipFilePath;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Mail\Mailable
     */
    public function build()
    {
        return $this->subject('Bulk CPD Certificates Download Completed')
                    ->html('<html>
                            <body>
                                <h1>Hello, ' . $this->user->name . '!</h1>
                                <p>Your bulk CPD certificates for CPD ID: ' . $this->cpd_id . ' have been successfully generated and are now ready for download.</p>
                                <p>The zip file containing all the certificates is attached to this email. You can download it directly from there.</p>
                                <a href="' . route($this->zipFilePath) . '">Download the zip file</a>
                                <p>Thank you for using our service!</p>
                            </body>
                            </html>');
                   
    }
}
