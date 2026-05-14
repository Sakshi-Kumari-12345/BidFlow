<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($auction->status !== 'ended') {
            return back()->with('error', 'You can only review after the auction ends.');
        }

        $highestBid = $auction->bids()->orderBy('amount', 'desc')->first();
        if (!$highestBid || $highestBid->buyer_id !== Auth::id()) {
            return back()->with('error', 'Only the winning bidder can leave a review.');
        }

        $existingReview = Review::where('auction_id', $auction->id)
            ->where('buyer_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this seller for this auction.');
        }

        Review::create([
            'auction_id' => $auction->id,
            'buyer_id' => Auth::id(),
            'seller_id' => $auction->seller_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
