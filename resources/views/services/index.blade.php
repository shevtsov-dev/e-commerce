@extends('layouts.app')

@section('title', 'Services')

@section('content')
    <section class="mt-3 py-5 text-center container">
        <h1>Services</h1>
        <a href="{{ route('services.create') }}" class="btn btn-primary mb-4">Create new service</a>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            @foreach($services as $service)
                @include('services._card', ['service' => $service])
            @endforeach
        </div>
    </section>
@endsection
