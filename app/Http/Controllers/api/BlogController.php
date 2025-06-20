<?php


namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController;
use App\Model\Blog;
use App\Model\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class BlogController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $query = Blog::orderBy('published_at', 'desc');

            // Filter by category if provided
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            $blogs = $query->paginate(10);
            //  $blogs = $query->get();
            return response()->json([
                'success' => true,
                'message' => 'Blog categories retrieved successfully',
                'data' => $blogs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve blog categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getBlogs(Request $request)
    {
        try {
            // $query = Blog::orderBy('published_at', 'desc');
            $query = Blog::with(['category'])->orderBy('published_at', 'desc');

            // Filter by category if provided
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // $blogs = $query->paginate(10);
            $blogs = $query->get();
            return response()->json([
                'success' => true,
                'message' => 'Blog categories retrieved successfully',
                'data' => $blogs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve blog categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        return response()->json($blog);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'nullable',
                'title' => 'required|max:255',
                'content' => 'required',
                'featured_image' => 'required',
                'excerpt' => 'required',
                'created_by' => 'required',
                'category_id' => 'required|exists:blog_categories,id',
                // 'categories' => 'nullable|array',
                // 'categories.*' => 'exists:blog_categories,id'
            ]);

            $custom = $this->custom;

            if (!empty($validated['id'])) {
                // Update
                $blog = Blog::find($validated['id']);

                if ($blog) {
                   
                    if ($request->hasFile('featured_image')) {
                        // Path to the old image
                        $oldImage = $blog->featured_image;

                        // Upload the new image
                        $blog_image = $custom->upload_image_blogs($request, 'featured_image');
                        $imagePath = $blog_image;

                        // Delete old image if it exists
                        if ($oldImage && file_exists(public_path('blogs/' . $oldImage))) {
                            unlink(public_path('blogs/' . $oldImage));
                        }

                        // Update image name in the blog model
                        $validated['featured_image'] = $imagePath;
                    }


                    $blog->update($validated);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Blog not found',
                    ], 404);
                }
            } else {
                // $imagePath = $request->file('featured_image')->store('blogs', 'public');

                if ($request->featured_image) {
                    $blog_image = $custom->upload_image_blogs($request, 'featured_image');

                    $imagePath = $blog_image;
                }

                $blog = Blog::create([
                    'title' => $validated['title'],
                    'slug' => Str::slug($validated['title']),
                    'content' => $validated['content'],
                    'featured_image' => $imagePath,
                    'excerpt' => $validated['excerpt'],
                    'category_id' => $validated['category_id'] ?? null,
                    'created_by' => $validated['created_by'] ?? null,
                    'published_at' => now()
                ]);
            }

            // Attach categories if provided
            if ($request->has('categories')) {
                $blog->categories()->attach($request->categories);
            }

            return response()->json([
                'success' => true,
                'message' => 'Blog submitted successfully',
                'data' => $blog
            ], 201);
        } catch (QueryException $e) {
            // Database-level duplicate key error (fallback)
            if ($e->getCode() == 23000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate entry detected in the database.',
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Database error',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit blog',
                'error' => $e->getMessage()
            ], 500);
        }
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
