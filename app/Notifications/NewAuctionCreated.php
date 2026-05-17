<?php

namespace App\Notifications;

use App\Models\Auction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAuctionCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $auction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Auction Alert: ' . $this->auction->title)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('A new auction has just been listed on our platform that you might be interested in.')
                    ->line('**Title:** ' . $this->auction->title)
                    ->line('**Starting Price:** $' . number_format($this->auction->starting_price, 2))
                    ->action('View Auction', route('auctions.show', $this->auction->id))
                    ->line('Don\'t miss out, place your bids now!');
    }
}
