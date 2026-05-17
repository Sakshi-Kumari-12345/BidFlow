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
            'amount' => ['required', 'numeric', 'max:99999999', function ($attribute, $value, $fail) use ($auction) {
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

            // Prevent MySQL's ON UPDATE CURRENT_TIMESTAMP from overriding end_time
            $auction->current_price = $request->amount;
            DB::table('auctions')
                ->where('id', $auction->id)
                ->update([
                    'current_price' => $request->amount,
                    'end_time' => DB::raw('end_time'),
                    'updated_at' => now(),
                ]);

            // Dispatch Event for WebSockets
            broadcast(new \App\Events\BidPlaced($auction, $bid))->toOthers();
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

        if ($auction->bids()->count() > 0) {
            return back()->with('error', 'You cannot use Buy It Now because bids have already been placed on this auction.');
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

        \Illuminate\Support\Facades\Mail::to(Auth::user())->send(new \App\Mail\AuctionWon($auction, Auth::user()));

        return back()->with('success', 'You successfully bought the item!');
    }
}
