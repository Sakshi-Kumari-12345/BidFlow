<?php

namespace App\Mail;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuctionWon extends Mailable
{
    use Queueable, SerializesModels;

    public $auction;
    public $winner;

    public function __construct(Auction $auction, User $winner)
    {
        $this->auction = $auction;
        $this->winner = $winner;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations! You won the auction for ' . $this->auction->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auction_won',
        );
    }
}
