<?php
use App\Models\User;
use App\Models\Auction;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

$seller = User::firstOrCreate(
    ['email' => 'seller@gmail.com'],
    ['name' => 'Test Seller', 'password' => Hash::make('password'), 'role' => 'seller']
);

$buyer = User::firstOrCreate(
    ['email' => 'buyer@gmail.com'],
    ['name' => 'Test Buyer', 'password' => Hash::make('password'), 'role' => 'buyer']
);

$category = Category::firstOrCreate(
    ['slug' => 'electronics'],
    ['name' => 'Electronics']
);

$auction = Auction::create([
    'seller_id' => $seller->id,
    'category_id' => $category->id,
    'title' => 'Vintage Rolex Submariner',
    'description' => 'A beautiful vintage watch in great condition.',
    'starting_price' => 5000.00,
    'current_price' => 5000.00,
    'buy_it_now_price' => 7500.00,
    'end_time' => Carbon::now()->addDays(7),
    'status' => 'active',
]);

echo "Auction ID: " . $auction->id . "\n";
