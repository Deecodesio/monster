<?php

namespace App\Http\Controllers\web;

use App\Model\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends BaseController
{
    public function index()
    {
        $categories = BlogCategory::orderBy('name', 'asc')->get();
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = BlogCategory::findOrFail($id);
        return response()->json($category);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $category = BlogCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null
        ]);

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $category->name = $validated['name'];
        $category->slug = Str::slug($validated['name']);
        $category->description = $validated['description'] ?? null;
        $category->save();

        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->delete();

        return response()->json(null, 204);
    }

    public function blogs($id)
    {
        $category = BlogCategory::findOrFail($id);
        $blogs = $category->blogs()->orderBy('published_at', 'desc')->paginate(10);

        return response()->json($blogs);
    }
}
