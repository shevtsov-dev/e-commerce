@php use Illuminate\Support\Facades\Auth; @endphp

<header class="fixed-top" data-bs-theme="dark">
    <div class="collapse text-bg-dark" id="navbarHeader">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-md-7 py-2">
                    <h4>About</h4>
                    <p>My name is Raman Shautsou. I'm a PHP Developer.</p>
                </div>
                <div class="col-sm-4 offset-md-1 py-2">
                    <h4>Contact</h4>
                    <ul class="list-unstyled d-flex gap-3">
                        <li><a href="#" class="text-white text-decoration-none">Twitter</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Facebook</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Email</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Phone</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a href="/" class="navbar-brand d-flex align-items-center">
                <strong>R. Shautsou E-Commerce Project</strong>
            </a>

            @php
                $user = Auth::user();
            @endphp

            <div class="d-flex">
                <div class="d-flex" style="gap: 10px;">
                    @if($user && $user->role_id === 2)
                    <form action="{{ route('search.index') }}" method="GET" class="d-flex my-2">
                            <input type="text" name="q" class="form-control me-2" placeholder="Search products..." required>
                            <button class="btn btn-outline-primary" type="submit">Search</button>
                        </form>
                    @endif
                    @guest
                        <a href="{{ route('auth.login') }}" class="navbar-brand align-items-center">Login</a>
                        <a href="{{ route('auth.registration') }}" class="navbar-brand align-items-center">Registration</a>
                    @endguest
                    @if($user && $user->role_id === 1)
                        <a href="{{ route('admin.dashboard') }}" class="navbar-brand d-flex align-items-center">
                            Dashboard
                        </a>
                    @endif
                    @auth
                         @if($user && $user->role_id === 2)
                             <a href="{{ route('cart.index') }}" class="navbar-brand d-flex align-items-center">
                                🛒 My cart
                             </a>
                         @endif
                        <form method="POST" action="{{ route('auth.logout') }}" class="my-2">
                            @csrf
                            <input type="submit" class="navbar-brand align-items-center justify-content-center" value="Log out">
                        </form>
                    @endauth
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                        aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation" id="navbarButton">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </div>
</header>

