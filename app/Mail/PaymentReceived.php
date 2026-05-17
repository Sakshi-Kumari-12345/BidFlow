<?php

namespace App\Mail;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $auction;
    public $seller;
    public $buyer;

    public function __construct(Auction $auction, User $seller, User $buyer)
    {
        $this->auction = $auction;
        $this->seller = $seller;
        $this->buyer = $buyer;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Received: ' . $this->auction->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payment_received',
        );
    }
}
