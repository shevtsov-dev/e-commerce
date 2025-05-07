@extends('layouts.app')

@section('title', 'Market')

@section('content')
    <section class="mt-5 py-5 text-center container">
        @auth
            <h1>Nice to see you, {{ Auth::user()->name }}!</h1>
        @endauth

        <div class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-gray-600 mb-8 text-lg">Quality products, fast shipping and great prices</p>
                <a href="{{ route('products.index') }}" class="button">
                    Go to catalogue
                </a>
            </div>
        </div>

        <div class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Popular products</h2>

                <div class="swiper">
                    <div class="swiper-wrapper">
                        @foreach ($products as $product)
                            <div class="swiper-slide product-card" data-url="{{ route('products.show', $product) }}" style="cursor: pointer;">
                                <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4">
                                    <h3 class="text-xl font-semibold text-gray-800">{{ $product->name }}</h3>
                                    <p class="text-gray-600 mt-2 mb-4">{{ Str::limit($product->description, 80) }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold text-blue-600">{{ $product->price }} BYN</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>

        <div class="bg-gray-100 py-16">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Subscribe for a news</h2>
                <p class="text-gray-600 mb-6">Keep up to date with the best offers and new products</p>
                <form action="{{ route('subscribe.store') }}" method="POST" class="flex flex-col sm:flex-row justify-center gap-4">
                    @csrf
                    <input type="email" name="email" placeholder="Enter your email"
                           class="px-4 py-2 w-full sm:w-80 rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <button type="submit"
                            class="bg-blue-600 text-black px-6 py-2 rounded hover:bg-blue-700 transition">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper('.swiper', {
                slidesPerView: 3,
                spaceBetween: 20,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach(function (card) {
                card.addEventListener('click', function () {
                    const url = card.getAttribute('data-url');
                    window.location.href = url;
                });
            });
        });
    </script>

    <style>
        .swiper-slide {
            width: 300px;
            height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
        }

        .swiper-slide img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 9999px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .button:hover {
            background-color: #1d4ed8;
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .button:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.5);
        }
    </style>
@endsection
