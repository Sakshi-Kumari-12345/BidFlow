@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 2rem auto; background: var(--bg-panel); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
    <h1 style="text-align: center; margin-bottom: 2rem;">Create Auction</h1>
    
    <form action="{{ route('auctions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Starting Price ($)</label>
                <input type="number" name="starting_price" class="form-control" step="0.01" min="0.01" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label class="form-label">End Time</label>
                <input type="datetime-local" name="end_time" class="form-control" required>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Item Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Create Auction</button>
    </form>
</div>
@endsection
