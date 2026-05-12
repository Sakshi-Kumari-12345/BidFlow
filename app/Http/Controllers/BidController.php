<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        if (Auth::user()->role !== 'buyer') {
            return back()->with('error', 'Only buyers can place bids.');
        }

        if ($auction->status !== 'active' || now()->greaterThan($auction->end_time)) {
            return back()->with('error', 'This auction has ended.');
        }

        $request->validate([
            'amount' => ['required', 'numeric', function ($attribute, $value, $fail) use ($auction) {
                if ($value <= $auction->current_price) {
                    $fail('Your bid must be higher than the current price ($' . $auction->current_price . ').');
                }
            }],
        ]);

        DB::transaction(function () use ($request, $auction) {
            $bid = Bid::create([
                'auction_id' => $auction->id,
                'buyer_id' => Auth::id(),
                'amount' => $request->amount,
            ]);

            $auction->update(['current_price' => $request->amount]);

            // TODO: Broadcast BidPlaced event for WebSockets
        });

        return back()->with('success', 'Bid placed successfully!');
    }
}
