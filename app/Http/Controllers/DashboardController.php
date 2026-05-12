<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function buyer()
    {
        if (Auth::user()->role !== 'buyer') abort(403);
        $bids = Bid::with('auction')->where('buyer_id', Auth::id())->latest()->get();
        return view('dashboard.buyer', compact('bids'));
    }

    public function seller()
    {
        if (Auth::user()->role !== 'seller') abort(403);
        $auctions = Auction::where('seller_id', Auth::id())->latest()->get();
        return view('dashboard.seller', compact('auctions'));
    }

    public function admin()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $auctions = Auction::latest()->paginate(10);
        return view('dashboard.admin', compact('auctions'));
    }
}
