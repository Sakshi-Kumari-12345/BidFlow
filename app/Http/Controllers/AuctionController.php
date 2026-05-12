<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auction::with('category')->where('status', 'active');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('current_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('current_price', '<=', $request->max_price);
        }

        if ($request->filled('ending_soon')) {
            $query->orderBy('end_time', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $auctions = $query->paginate(12);
        $categories = Category::all();

        return view('auctions.index', compact('auctions', 'categories'));
    }

    public function show(Auction $auction)
    {
        $auction->load(['category', 'seller', 'bids.buyer']);
        return view('auctions.show', compact('auction'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'seller') abort(403, 'Unauthorized');
        
        if (Category::count() === 0) {
            $cats = ['Electronics', 'Art & Collectibles', 'Vehicles', 'Fashion', 'Real Estate', 'Jewelry'];
            foreach ($cats as $cat) {
                Category::firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($cat)],
                    ['name' => $cat]
                );
            }
        }
        
        $categories = Category::all();
        return view('auctions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'seller') abort(403, 'Unauthorized');

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'starting_price' => 'required|numeric|min:0.01',
            'end_time' => 'required|date|after:now',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('auctions', 'public');
        }

        $auction = Auction::create([
            'seller_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'starting_price' => $request->starting_price,
            'current_price' => $request->starting_price,
            'end_time' => $request->end_time,
            'image_path' => $imagePath,
            'status' => 'active'
        ]);

        return redirect()->route('auctions.show', $auction)->with('success', 'Auction created successfully!');
    }
}
