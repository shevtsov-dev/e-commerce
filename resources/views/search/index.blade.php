@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h2>Search results for: "{{ $query }}"</h2>

        @if($products->isEmpty())
            <p class="text-muted">No results found.</p>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-3">
                @foreach($products as $product)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                                        {{ $product->name }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted">
                                    {{ Str::limit($product->description, 100) }}
                                </p>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <span class="text-primary fw-bold">{{ number_format($product->price, 2) }} BYN</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="d-flex justify-content-center mt-4">
            {{ $products->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
