<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function ($query) {
            $query->where('is_enabled', true)
                ->where('stock', '>', 0)
                ->orderByDesc('created_at');
        }])->orderBy('name')->get();

        $totalProducts = $categories->sum(fn ($category) => $category->products->count());

        return view('categories', compact('categories', 'totalProducts'));
    }
}
