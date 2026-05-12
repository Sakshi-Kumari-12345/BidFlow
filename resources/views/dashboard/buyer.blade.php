@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1>Buyer Dashboard</h1>
    <p style="color: var(--text-muted);">Welcome back, {{ Auth::user()->name }}! Here are your bidding activities.</p>
</div>

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
                    <td style="padding: 1rem 0;">
                        <a href="{{ route('auctions.show', $bid->auction) }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">View</a>
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
@endsection
