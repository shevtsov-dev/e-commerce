@extends('layouts.app')

@section('title', 'Market')

@section('content')
    <section class="mt-5 py-3 text-center container">
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

                <!-- Swiper -->
                <div class="swiper">
                    <div class="swiper-wrapper">
                        @foreach ($products as $product)
                            <div class="swiper-slide product-card" data-url="{{ route('products.show', $product) }}" style="cursor: pointer;">
                                <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                         class="w-full h-48 object-cover rounded-md mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800">{{ $product->name }}</h3>
                                    <p class="text-gray-600 mt-2 mb-4">{{ Str::limit($product->description, 80) }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold text-blue-600">{{ $product->price }} BYN</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Навигационные кнопки -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
                <!-- Конец Swiper -->
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

    <!-- Инициализация Swiper -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper('.swiper', {
                slidesPerView: 3,       // Количество слайдов, которые показываются одновременно
                spaceBetween: 20,       // Расстояние между слайдами
                loop: true,             // Бесконечный цикл
                navigation: {
                    nextEl: '.swiper-button-next',   // Кнопка "следующий"
                    prevEl: '.swiper-button-prev',   // Кнопка "предыдущий"
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,  // На маленьких экранах показывать 1 слайд
                    },
                    768: {
                        slidesPerView: 2,  // На экранах средних размеров показывать 2 слайда
                    },
                    1024: {
                        slidesPerView: 3,  // На больших экранах показывать 3 слайда
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
                    window.location.href = url; // Переход на ссылку товара
                });
            });
        });
    </script>

    <!-- Стили для слайдера -->
    <style>
        .swiper-slide {
            width: 300px;  /* фиксированная ширина */
            height: 400px; /* фиксированная высота */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
        }

        .swiper-slide img {
            width: 100%;
            height: 200px; /* фиксированная высота для изображений */
            object-fit: cover; /* сохраняет пропорции изображения */
        }

        .button {
            display: inline-block;
            background-color: #2563eb; /* Синий цвет */
            color: white;
            padding: 0.75rem 2rem; /* Паддинги для кнопки */
            border-radius: 9999px; /* Закругленные углы */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Тень */
            text-align: center;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .button:hover {
            background-color: #1d4ed8; /* Темнее синий при наведении */
            transform: scale(1.05); /* Увеличение при наведении */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Увеличение тени при наведении */
        }

        .button:focus {
            outline: none; /* Убираем обводку */
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.5); /* Фокусное кольцо */
        }
    </style>
@endsection
