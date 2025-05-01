@extends('layouts.app')

@section('title', 'Market')

@section('content')
    <section class="mt-5 py-3 text-center container">
        <h1>Product market with best prices</h1>
        @auth
            <h2>Nice to see you, {{ Auth::user()->name }}!</h2>
        @endauth
        <div class="d-flex gap-3" style="align-items: center; justify-content: center">
            @auth
                <a href="{{ route('products.index') }}" class="btn btn-primary">Check all products</a>
                @if(auth()->user()?->role->name === 'admin')
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">Check all categories</a>
                    <a href="{{ route('producers.index') }}" class="btn btn-primary">Check all producers</a>
                    <a href="{{ route('services.index') }}" class="btn btn-primary">Check all services</a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Check all users</a>
                    <a href="{{ route('admin.currency-rates') }}" class="btn btn-primary">Check actual currency</a>
                    <form action="{{ route('admin.export') }}" method="GET" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Export all products</button>
                    </form>
                @endif
            @endauth
        </div>
    </section>
@endsection
