@extends('layouts.app')

@section('title', 'Currency')

@section('content')
    <style>
        .currency-section {
            max-width: 800px;
            margin: 60px auto;
            padding: 40px;
            text-align: center;
            background: #f9f9f9;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .currency-section h1 {
            font-size: 32px;
            margin-bottom: 24px;
            color: #333;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: inline-block;
        }

        table.currency-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table.currency-table th,
        table.currency-table td {
            padding: 14px 18px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table.currency-table th {
            background-color: #007bff;
            color: white;
        }

        table.currency-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .update-btn {
            padding: 12px 24px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .update-btn:hover {
            background-color: #0056b3;
        }
    </style>

    <section class="currency-section">
        <h1>Only Actual Currency</h1>

        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <table class="currency-table">
            <thead>
            <tr>
                <th>Currency</th>
                <th>Rate</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($rates as $currency => $rate)
                <tr>
                    <td>{{ $currency }}</td>
                    <td>{{ $rate }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <form action="{{ url('admin/currency-rates/update') }}" method="POST">
            @csrf
            <button type="submit" class="update-btn">Update Currency Rates</button>
        </form>
    </section>
@endsection
