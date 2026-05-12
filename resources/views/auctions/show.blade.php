@extends('layouts.app')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 2rem;">
    
    <!-- Image Section -->
    <div>
        @if($auction->image_path)
            <img src="{{ asset('storage/' . $auction->image_path) }}" alt="{{ $auction->title }}" style="width: 100%; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
        @else
            <div style="width: 100%; height: 400px; background: var(--bg-panel); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                No Image Available
            </div>
        @endif
    </div>

    <!-- Details Section -->
    <div style="background: var(--bg-panel); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <h1>{{ $auction->title }}</h1>
            <span class="badge {{ $auction->status === 'active' ? 'badge-active' : 'badge-ended' }}">
                {{ ucfirst($auction->status) }}
            </span>
        </div>
        
        <p style="color: var(--text-muted); margin-bottom: 2rem;">Sold by: {{ $auction->seller->name ?? 'Unknown' }}</p>
        
        <div style="background: var(--bg-dark); padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 2rem; text-align: center;">
            <p style="color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px;">Current Bid</p>
            <div style="font-size: 3rem; font-weight: 800; color: var(--accent-primary); line-height: 1.2;">
                ${{ number_format($auction->current_price, 2) }}
            </div>
            
            <p style="color: var(--accent-alert); font-weight: 600; margin-top: 0.5rem;" id="countdown">
                Ends: {{ \Carbon\Carbon::parse($auction->end_time)->format('M d, Y H:i:s') }}
            </p>
        </div>

        @if($auction->status === 'active' && now()->lessThan($auction->end_time))
            @auth
                @if(Auth::user()->role === 'buyer')
                    <form action="{{ route('bids.store', $auction) }}" method="POST" style="display: flex; gap: 1rem;">
                        @csrf
                        <div style="flex: 1;">
                            <input type="number" name="amount" class="form-control" step="0.01" min="{{ $auction->current_price + 0.01 }}" placeholder="Enter bid amount" required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 150px;">Place Bid</button>
                    </form>
                @else
                    <div class="alert alert-error" style="text-align: center;">Only buyers can place bids.</div>
                @endif
            @else
                <div style="text-align: center;">
                    <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%;">Login to Bid</a>
                </div>
            @endauth
        @else
            <div class="alert alert-error" style="text-align: center;">This auction has ended.</div>
        @endif

        <h3 style="margin-top: 3rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Description</h3>
        <p style="margin-top: 1rem; color: var(--text-muted);">{{ $auction->description }}</p>

        <h3 style="margin-top: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Bid History</h3>
        <div style="margin-top: 1rem; max-height: 200px; overflow-y: auto;">
            @forelse($auction->bids->sortByDesc('created_at') as $bid)
                <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                    <span style="color: var(--text-muted);">{{ $bid->buyer->name }}</span>
                    <span style="font-weight: 600; color: var(--accent-primary);">${{ number_format($bid->amount, 2) }}</span>
                </div>
            @empty
                <p style="color: var(--text-muted);">No bids yet. Be the first!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
