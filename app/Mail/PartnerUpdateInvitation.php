<?php

namespace App\Mail;

use App\Models\Partner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PartnerUpdateInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The partner instance.
     *
     * @var \App\Models\Partner
     */
    public $partner;

    /**
     * The update token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Partner  $partner
     * @param  string  $token
     * @return void
     */
    public function __construct(Partner $partner, string $token)
    {
        $this->partner = $partner;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Update Your Partner Profile')
            ->markdown('emails.partners.update-invitation')
            ->with([
                'partner' => $this->partner,
                'url' => route('partners.edit-with-token', $this->token),
                'expiration' => '48 hours'
            ]);
    }
}
