<?php

namespace App\Events;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BidPlaced implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $auction_id;
    public $amount;
    public $buyer_name;
    public $formatted_amount;

    public function __construct(Auction $auction, Bid $bid)
    {
        $this->auction_id = $auction->id;
        $this->amount = $bid->amount;
        $this->buyer_name = $bid->buyer->name;
        $this->formatted_amount = number_format($bid->amount, 2);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('auction.' . $this->auction_id),
        ];
    }
    
    public function broadcastAs(): string
    {
        return 'bid.placed';
    }
}
