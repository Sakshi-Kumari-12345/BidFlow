@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1>Admin Dashboard</h1>
    <p style="color: var(--text-muted);">System overview and moderation.</p>
</div>

<div class="card" style="padding: 2rem;">
    <h2 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">All Auctions</h2>
    
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 2px solid var(--border-color); color: var(--text-muted);">
                <th style="padding: 1rem 0;">ID</th>
                <th style="padding: 1rem 0;">Title</th>
                <th style="padding: 1rem 0;">Seller</th>
                <th style="padding: 1rem 0;">Status</th>
                <th style="padding: 1rem 0;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($auctions as $auction)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem 0; color: var(--text-muted);">#{{ $auction->id }}</td>
                    <td style="padding: 1rem 0; font-weight: 500;">{{ $auction->title }}</td>
                    <td style="padding: 1rem 0;">{{ $auction->seller->name ?? 'N/A' }}</td>
                    <td style="padding: 1rem 0;">
                        <span class="badge {{ $auction->status === 'active' ? 'badge-active' : 'badge-ended' }}">
                            {{ ucfirst($auction->status) }}
                        </span>
                    </td>
                    <td style="padding: 1rem 0;">
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 2rem;">
        {{ $auctions->links() }}
    </div>
</div>
@endsection
