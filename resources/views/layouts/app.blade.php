<!doctype html>
<html lang="en" data-bs-theme="auto">
@include('layouts.head')
<body>
@include('layouts.navbar')

<main>
    @yield('content')
</main>

@include('layouts.footer')

@if(session('success'))
    <script>
        console.log("{{ session('success') }}");
    </script>
@endif

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

@stack('scripts')

</body>
</html>
