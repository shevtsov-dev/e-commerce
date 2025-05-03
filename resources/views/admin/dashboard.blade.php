@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <section class="mt-7 pt-5 text-center container">
        <h1>Admin Dashboard</h1>
        <p>Welcome to the admin panel.</p>
    </section>

    <div class="d-flex gap-3" style="align-items: center; justify-content: center">
        @auth
            @if(auth()->user()?->role->name === 'admin')
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create new user</a>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Check all products</a>
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Check all categories</a>
                <a href="{{ route('admin.currency-rates') }}" class="btn btn-primary">Check actual currency</a>
                <form action="{{ route('admin.export') }}" method="GET" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Export all products</button>
                </form>
            @endif
        @endauth
    </div>

    <section class="text-center container">

        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">
                    <a href="{{ route('admin.users.index', ['sort' => 'id']) }}" class="text-decoration-none">
                        # <i class="bi bi-sort"></i>
                    </a>
                </th>
                <th scope="col">
                    <a href="{{ route('admin.users.index', ['sort' => 'name']) }}" class="text-decoration-none">
                        Name <i class="bi bi-sort"></i>
                    </a>
                </th>
                <th scope="col">
                    <a href="{{ route('admin.users.index', ['sort' => 'email']) }}" class="text-decoration-none">
                        Email <i class="bi bi-sort"></i>
                    </a>
                </th>
                <th scope="col">
                    <a href="{{ route('admin.users.index', ['sort' => 'role']) }}" class="text-decoration-none">
                        Role <i class="bi bi-sort"></i>
                    </a>
                </th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role ? $user->role->name : 'No role' }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>

    <div class="d-flex justify-content-center mt-4">
        {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
@endsection
