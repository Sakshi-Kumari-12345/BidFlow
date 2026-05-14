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
        if ($auction->seller_id === Auth::id()) {
            return back()->with('error', 'You cannot bid on your own auction.');
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

    public function buyItNow(Request $request, Auction $auction)
    {
        if ($auction->seller_id === Auth::id()) {
            return back()->with('error', 'You cannot buy your own auction.');
        }

        if ($auction->status !== 'active' || now()->greaterThan($auction->end_time)) {
            return back()->with('error', 'This auction has ended.');
        }

        if (!$auction->buy_it_now_price) {
            return back()->with('error', 'This auction does not have a Buy It Now price.');
        }

        DB::transaction(function () use ($auction) {
            Bid::create([
                'auction_id' => $auction->id,
                'buyer_id' => Auth::id(),
                'amount' => $auction->buy_it_now_price,
            ]);

            $auction->update([
                'current_price' => $auction->buy_it_now_price,
                'end_time' => now(),
                'status' => 'ended'
            ]);
        });

        return back()->with('success', 'You successfully bought the item!');
    }
}
