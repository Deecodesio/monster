<?php


namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController;

use App\Model\CareerJobCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CareerJobCategoryController extends BaseController
{
    public function index()
    {
        try {
            $categories = CareerJobCategory::orderBy('name', 'asc')->get();
            return response()->json([
                'success' => true,
                'message' => 'Career job categories retrieved successfully',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve career job categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = CareerJobCategory::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Career job2 category retrieved successfully',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Career job category not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'id' => 'nullable',
                'description' => 'nullable'
            ]);

            if (!empty($validated['id'])) {
                // Update
                $category = CareerJobCategory::find($validated['id']);

                if ($category) {
                    $category->update($validated);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Job category not found',
                    ], 404);
                }
            } else {
                $category = CareerJobCategory::create([
                    'name' => $validated['name'],
                    'slug' => Str::slug($validated['name']),
                    'description' => $validated['description'] ?? null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Job Category submitted successfully',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit career job category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $category = CareerJobCategory::findOrFail($id);

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
        $category = CareerJobCategory::findOrFail($id);
        $category->delete();

        return response()->json(null, 204);
    }

    public function blogs($id)
    {
        $category = CareerJobCategory::findOrFail($id);
        $blogs = $category->blogs()->orderBy('published_at', 'desc')->paginate(10);

        return response()->json($blogs);
    }
}
