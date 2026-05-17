@extends('layouts.app')

@section('content')
<div style="max-width: 400px; margin: 4rem auto; background: var(--bg-panel); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-lg);">
    <h2 style="text-align: center; margin-bottom: 2rem; font-weight: 800;">Welcome Back</h2>
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            @error('email')<span style="color: var(--accent-alert); font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')<span style="color: var(--accent-alert); font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Login</button>
    </form>
    
    <div style="text-align: center; margin-top: 1.5rem;">
        <span style="color: var(--text-muted);">Don't have an account?</span>
        <a href="{{ route('register') }}" style="font-weight: 600;">Register here</a>
    </div>
</div>
@endsection
