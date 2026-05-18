@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1>My Dashboard</h1>
        <p style="color: var(--text-muted);">Welcome back, {{ Auth::user()->name }}! Manage your bids and auctions.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('profile.edit') }}" class="btn btn-outline">Edit Profile</a>
        <a href="{{ route('auctions.create') }}" class="btn btn-primary">Create New Auction</a>
    </div>
</div>

<div style="display: grid; gap: 2rem;">
    <!-- Bids Section -->
    <div class="card" style="padding: 2rem;">
        <h2 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Your Bids</h2>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color); color: var(--text-muted);">
                    <th style="padding: 1rem 0;">Auction Item</th>
                    <th style="padding: 1rem 0;">Your Bid</th>
                    <th style="padding: 1rem 0;">Current Highest</th>
                    <th style="padding: 1rem 0;">Status</th>
                    <th style="padding: 1rem 0;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bids as $bid)
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 1rem 0; font-weight: 500;">
                            <a href="{{ route('auctions.show', $bid->auction) }}">{{ $bid->auction->title }}</a>
                        </td>
                        <td style="padding: 1rem 0;">${{ number_format($bid->amount, 2) }}</td>
                        <td style="padding: 1rem 0; color: {{ $bid->amount >= $bid->auction->current_price ? 'var(--accent-primary)' : 'var(--accent-alert)' }};">
                            ${{ number_format($bid->auction->current_price, 2) }}
                        </td>
                        <td style="padding: 1rem 0;">
                            @if($bid->auction->status === 'active')
                                @if($bid->amount >= $bid->auction->current_price)
                                    <span class="badge badge-active">Winning</span>
                                @else
                                    <span class="badge badge-ended">Outbid</span>
                                @endif
                            @else
                                @if($bid->amount >= $bid->auction->current_price)
                                    <span class="badge badge-active" style="background-color: var(--accent-purple); color: #fff;">Won!</span>
                                @else
                                    <span class="badge" style="background-color: var(--bg-dark); color: var(--text-muted);">Lost</span>
                                @endif
                            @endif
                        </td>
                        <td style="padding: 1rem 0; display: flex; gap: 0.5rem;">
                            <a href="{{ route('auctions.show', $bid->auction) }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">View</a>
                            @if($bid->auction->status === 'ended' && $bid->amount >= $bid->auction->current_price)
                                @if($bid->auction->payment_status !== 'paid')
                                    <a href="{{ route('checkout.session', $bid->auction) }}" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; background-color: var(--accent-success); border-color: var(--accent-success);">Pay Now</a>
                                @else
                                    @php
                                        $hasReviewed = \App\Models\Review::where('auction_id', $bid->auction->id)->where('buyer_id', Auth::id())->exists();
                                    @endphp
                                    @if(!$hasReviewed)
                                        <button onclick="openReviewModal({{ $bid->auction->id }})" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; background-color: #fbbf24; border-color: #fbbf24; color: #000;">Leave Review</button>
                                    @endif
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem 0; text-align: center; color: var(--text-muted);">
                            You haven't placed any bids yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Auctions Section -->
    <div class="card" style="padding: 2rem;">
        <h2 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Your Auctions</h2>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color); color: var(--text-muted);">
                    <th style="padding: 1rem 0;">Title</th>
                    <th style="padding: 1rem 0;">Starting Price</th>
                    <th style="padding: 1rem 0;">Current Bid</th>
                    <th style="padding: 1rem 0;">Status</th>
                    <th style="padding: 1rem 0;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auctions as $auction)
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 1rem 0; font-weight: 500;">
                            <a href="{{ route('auctions.show', $auction) }}">{{ $auction->title }}</a>
                        </td>
                        <td style="padding: 1rem 0;">${{ number_format($auction->starting_price, 2) }}</td>
                        <td style="padding: 1rem 0; color: var(--accent-primary); font-weight: 600;">
                            ${{ number_format($auction->current_price, 2) }}
                        </td>
                        <td style="padding: 1rem 0;">
                            <span class="badge {{ $auction->status === 'active' ? 'badge-active' : 'badge-ended' }}">
                                {{ ucfirst($auction->status) }}
                            </span>
                        </td>
                        <td style="padding: 1rem 0;">
                            <a href="{{ route('auctions.show', $auction) }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem 0; text-align: center; color: var(--text-muted);">
                            You have no active auctions.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Review Modal -->
<div id="reviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); align-items: center; justify-content: center; z-index: 1000;">
    <div style="background: var(--bg-panel); padding: 2rem; border-radius: var(--radius-lg); width: 100%; max-width: 400px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem;">Rate the Seller</h3>
        <form id="reviewForm" method="POST" action="">
            @csrf
            <div class="form-group">
                <label class="form-label">Rating (1-5)</label>
                <select name="rating" class="form-control" required>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Good</option>
                    <option value="3">3 - Average</option>
                    <option value="2">2 - Poor</option>
                    <option value="1">1 - Terrible</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Comment (Optional)</label>
                <textarea name="comment" class="form-control" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="button" onclick="closeReviewModal()" class="btn btn-outline" style="flex: 1;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 1;">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReviewModal(auctionId) {
        document.getElementById('reviewModal').style.display = 'flex';
        document.getElementById('reviewForm').action = '/auctions/' + auctionId + '/reviews';
    }
    function closeReviewModal() {
        document.getElementById('reviewModal').style.display = 'none';
    }
</script>
@endsection
