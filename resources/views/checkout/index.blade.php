@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Checkout</h1>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" required class="form-control">
            </div>

            <div class="mb-3">
                <strong>Total: ${{ number_format($total, 2) }}</strong>
            </div>

            <button type="submit" class="btn btn-primary">Pay Now</button>
        </form>
    </div>
@endsection
