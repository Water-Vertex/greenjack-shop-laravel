<?php

namespace App\Mail;

use App\Models\RepairRequest;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class SimpleRepairConfirmation extends Mailable
{
    public $repairRequest;

    public function __construct(RepairRequest $repairRequest)
    {
        $this->repairRequest = $repairRequest;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✓ Repair Request Confirmed - #' . $this->repairRequest->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.simple-repair-confirmation',
        );
    }
}
