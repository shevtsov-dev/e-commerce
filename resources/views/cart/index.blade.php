@extends('layouts.app')

@section('content')
    <section class="container py-5">
        <h1 class="text-center text-primary mb-4">🛒 Your Cart</h1>

        @if(session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(empty($cart))
            <div class="text-center text-muted">Your cart is empty.</div>
        @else
            <div class="list-group">
                @php $total = 0; @endphp

                @foreach($cart as $id => $item)
                    @php $total += $item['price'] * $item['quantity']; @endphp

                    <div class="list-group-item d-flex justify-content-between align-items-center p-4 shadow-sm rounded">
                        <div>
                            <h5 class="mb-2 text-dark">{{ $item['name'] }}</h5>
                            <p class="text-muted mb-1">Price: {{ number_format($item['price'], 2) }} BYN</p>
                            <p class="text-muted">Quantity: {{ $item['quantity'] }}</p>
                        </div>
                        <form action="{{ route('cart.remove', $id) }}" method="POST" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Remove</button>
                        </form>
                    </div>
                @endforeach

                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                    <div class="h5 font-weight-bold">Total:</div>
                    <div class="h4 text-primary">{{ number_format($total, 2) }} BYN</div>
                </div>

                <div class="d-flex justify-content-between mt-5">
                    <form action="{{ route('cart.clear') }}" method="POST" class="w-100">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100">Clear Cart</button>
                    </form>

                    <a href="{{ route('checkout.index') }}"
                       class="btn btn-success w-100 ms-3">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        @endif
    </section>
@endsection
