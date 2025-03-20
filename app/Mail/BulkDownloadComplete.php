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
        return $this->subject('Bulk Certificates from IPPU')
                    ->html('<html>
                            <body>
                                <h1>Hello, ' . $this->user->name . '!</h1>
                                <p>Your bulk certificates for ID: ' . $this->cpd_id . ' have been successfully generated and are now ready for download.</p>
                                <p>Click the link below to download the zipped file. You can download it directly from there.</p>
                                <a href="' . url('downloadZip',$this->zipFilePath) . '">Download the zip file</a>
                                <p>Thank you for using our service!</p>
                            </body>
                            </html>');
                   
    }
}
