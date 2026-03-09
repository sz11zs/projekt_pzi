<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class CategoryController extends Controller
{
    public function index(): Collection
    {
        return Category::get();
    }

    public function store(Request $request): Category
    {
        $data = $request->validate([
            'name'=>'required|unique:categories,name',
        ]);
        return Category::create($data);
    }

    public function show(string $id): Category
    {
        return Category::findOrFail($id);
    }

    public function update(Request $request, string $id): Category
    {
        $category = Category::findOrFail($id);

        $data =$request->validate([
            'name'=>'required|unique:categories,name,'.$category->id,
        ]);

        $category->update($data);

        return $category;
    }

    public function destroy(string $id): void
    {
        Category::findOrFail($id) ->delete();
    }
}
