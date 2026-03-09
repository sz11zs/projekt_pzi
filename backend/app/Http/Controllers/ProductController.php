<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class ProductController extends Controller
{
  public function index(Request $request)
{
    $query = Product::query()->with('category');

    // filter po kategoriji
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // search (case-insensitive; MySQL je najčešće već case-insensitive)
    if ($request->filled('q')) {
        $q = $request->q;
        $query->where('name', 'like', "%{$q}%");
    }

    return $query->get();
}

   public function store(Request $request): Product
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required',
            'quantity' => 'required',
            'porijeklo'=> 'nullable',
            'klasa' => 'nullable',
            'image' => 'nullable',
            'is_active' => 'required',
        ]);
        return Product::create($data);
    }

    public function show(string $id): Product
    {
        return Product::with('category')->findOrFail($id);
    }

    public function update(Request $request, string $id): Product
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required',
            'quantity' => 'required',
            'porijeklo'=> 'nullable',
            'klasa' => 'nullable',
            'image' => 'nullable',
            'is_active' => 'required',
        ]);

        $product->update($data);

        return $product;
    }

    public function destroy(string $id): void
    {
        Product::findOrFail($id)->delete();
    }
}
