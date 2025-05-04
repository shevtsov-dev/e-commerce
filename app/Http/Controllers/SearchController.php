<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $searchTerm = mb_strtolower($request->input('q'));

        $products = Product::query()
            ->when(strlen($searchTerm) === 1, function ($query) use ($searchTerm) {
                return $query->whereRaw('LOWER(name) LIKE ?', [$searchTerm . '%']);
            }, function ($query) use ($searchTerm) {
                return $query->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%']);
            })
            ->orderBy('name')
            ->paginate(9);

        return view('search.index', [
            'products' => $products,
            'query' => $searchTerm
        ]);
    }
}
