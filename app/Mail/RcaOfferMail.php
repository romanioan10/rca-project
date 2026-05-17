<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class RcaOfferMail extends Mailable
{
    public function __construct(
        public array $offer,
        public ?string $pdfContent = null,
        public ?string $fileName = null
    ) {}

    public function build()
    {
        $mail = $this->subject('Oferta ta RCA')
            ->view('emails.rca-offer');

        if ($this->pdfContent && $this->fileName) {
            $mail->attachData($this->pdfContent, $this->fileName, [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}