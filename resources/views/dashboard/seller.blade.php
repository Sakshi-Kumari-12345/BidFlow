@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1>Seller Dashboard</h1>
        <p style="color: var(--text-muted);">Manage your auctions and track bids.</p>
    </div>
    <a href="{{ route('auctions.create') }}" class="btn btn-primary">Create New Auction</a>
</div>

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
@endsection
