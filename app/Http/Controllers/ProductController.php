<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    public function index()
    {
        $product = $this->baseProductQuery()->paginate(12);
        $discount = $this->activeDiscount();

        return view('products', [
            'product' => $product,
            'discount' => $discount,
            'search' => null,
        ]);
    }

    public function search(Request $request)
    {
        $searchRaw = trim((string) $request->input('query', ''));
        $searchTerm = mb_strtolower($searchRaw, 'UTF-8');

        $productQuery = $this->baseProductQuery();

        if ($searchRaw !== '') {
            $productQuery->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%']);
        }

        $product = $productQuery->paginate(12)->appends(['query' => $searchRaw]);
        $discount = $this->activeDiscount();

        if ($request->ajax()) {
            $html = view('partials.products-grid', [
                'product' => $product,
                'activeDiscount' => optional($discount)->percentage ?? 0,
                'productCount' => $product->total(),
                'search' => $searchRaw,
            ])->render();

            return response()->json(['html' => $html]);
        }

        return view('products', [
            'product' => $product,
            'discount' => $discount,
            'search' => $searchRaw,
        ]);
    }

    protected function baseProductQuery() : Builder
    {
        return Product::query()
            ->where('stock', '>', 0)
            ->where('is_enabled', 1)
            ->orderByDesc('created_at');
    }

    protected function activeDiscount()
    {
        return Discount::where('status', 'active')->first();
    }
}
