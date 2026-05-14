<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $bids = Bid::with('auction')->where('buyer_id', Auth::id())->latest()->get();
        $auctions = Auction::where('seller_id', Auth::id())->latest()->get();
        return view('dashboard.index', compact('bids', 'auctions'));
    }

    public function admin()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $auctions = Auction::latest()->paginate(10);
        return view('dashboard.admin', compact('auctions'));
    }
}
