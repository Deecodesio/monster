<?php

namespace App\Http\Controllers\web;

use App\Model\Blog;
use App\Model\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends BaseController
{
    public function index(Request $request)
    {
        $query = Blog::orderBy('published_at', 'desc');

        // Filter by category if provided
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $blogs = $query->paginate(10);
        return response()->json($blogs);
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        return response()->json($blog);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'featured_image' => 'required|image',
            'excerpt' => 'required',
            'category_id' => 'nullable|exists:blog_categories,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:blog_categories,id'
        ]);

        $imagePath = $request->file('featured_image')->store('blogs', 'public');

        $blog = Blog::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            'excerpt' => $validated['excerpt'],
            'category_id' => $validated['category_id'] ?? null,
            'published_at' => now()
        ]);

        // Attach categories if provided
        if ($request->has('categories')) {
            $blog->categories()->attach($request->categories);
        }

        return response()->json($blog, 201);
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'featured_image' => 'nullable|image',
            'excerpt' => 'required',
            'category_id' => 'nullable|exists:blog_categories,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:blog_categories,id'
        ]);

        if ($request->hasFile('featured_image')) {
            // Delete old image if it exists
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }

            $imagePath = $request->file('featured_image')->store('blogs', 'public');
            $blog->featured_image = $imagePath;
        }

        $blog->title = $validated['title'];
        $blog->slug = Str::slug($validated['title']);
        $blog->content = $validated['content'];
        $blog->excerpt = $validated['excerpt'];
        $blog->category_id = $validated['category_id'] ?? null;
        $blog->save();

        // Sync categories if provided
        if ($request->has('categories')) {
            $blog->categories()->sync($request->categories);
        }

        return response()->json($blog);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        // Delete featured image if it exists
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return response()->json(null, 204);
    }

    public function byCategory($categoryId)
    {
        $category = BlogCategory::findOrFail($categoryId);
        $blogs = $category->blogs()->orderBy('published_at', 'desc')->paginate(10);

        return response()->json([
            'category' => $category,
            'blogs' => $blogs
        ]);
    }
}
