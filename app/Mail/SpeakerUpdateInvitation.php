<?php

namespace App\Mail;

use App\Models\Speaker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SpeakerUpdateInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $speaker;
    public $token;

    /**
     * Create a new message instance.
     *
     * @param Speaker $speaker
     * @param string $token
     * @return void
     */
    public function __construct(Speaker $speaker, $token)
    {
        $this->speaker = $speaker;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Update Your Speaker Profile')
                    ->markdown('emails.speaker-update-invitation');
    }
}
