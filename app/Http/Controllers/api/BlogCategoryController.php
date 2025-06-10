<?php


namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController;

use App\Model\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
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

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'id' => 'nullable',
            'description' => 'nullable'
        ]);

        if (!empty($validated['id'])) {
            // Update
            $category = BlogCategory::find($validated['id']);

            if ($category) {
                $category->update($validated);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog category not found',
                ], 404);
            }
        } else {
            $category = BlogCategory::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'] ?? null
            ]);
        }



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
