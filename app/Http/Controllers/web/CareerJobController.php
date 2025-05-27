<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CareerJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CareerJobController extends Controller
{
    /**
     * Display a listing of career jobs with filters
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = CareerJob::with(['city', 'state', 'creator']);

            // Apply filters
            if ($request->has('location_id') && $request->location_id) {
                $query->byLocation($request->location_id);
            }

            if ($request->has('state_id') && $request->state_id) {
                $query->byState($request->state_id);
            }

            if ($request->has('created_by') && $request->created_by) {
                $query->byCreator($request->created_by);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Date filters
            if ($request->has('start_date') || $request->has('end_date')) {
                $query->dateRange($request->start_date, $request->end_date);
            }

            // Search filter
            if ($request->has('search') && $request->search) {
                $query->where('job_name', 'LIKE', '%' . $request->search . '%');
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $jobs = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Career jobs retrieved successfully',
                'data' => $jobs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve career jobs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created career job
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'job_name' => 'required|string|max:255',
                'job_details' => 'required|string',
                'location_id' => 'required|exists:add_city,id',
                'state_id' => 'required|exists:add_city,state_id',
                'created_by' => 'required|exists:roles,id',
                'status' => ['sometimes', Rule::in(['active', 'inactive'])]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $job = CareerJob::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Career job created successfully',
                'data' => $job->load(['city', 'state', 'creator'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create career job',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified career job
     */
    public function show($id): JsonResponse
    {
        try {
            $job = CareerJob::with(['city', 'state', 'creator'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Career job retrieved successfully',
                'data' => $job
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Career job not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified career job
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $job = CareerJob::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'job_name' => 'sometimes|required|string|max:255',
                'job_details' => 'sometimes|required|string',
                'location_id' => 'sometimes|required|exists:add_city,id',
                'state_id' => 'sometimes|required|exists:add_city,state_id',
                'created_by' => 'sometimes|required|exists:roles,id',
                'status' => ['sometimes', Rule::in(['active', 'inactive'])]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $job->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Career job updated successfully',
                'data' => $job->load(['city', 'state', 'creator'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update career job',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified career job
     */
    public function destroy($id): JsonResponse
    {
        try {
            $job = CareerJob::findOrFail($id);
            $job->delete();

            return response()->json([
                'success' => true,
                'message' => 'Career job deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete career job',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status of career job
     */
    public function toggleStatus($id): JsonResponse
    {
        try {
            $job = CareerJob::findOrFail($id);
            $job->status = $job->status === 'active' ? 'inactive' : 'active';
            $job->save();

            return response()->json([
                'success' => true,
                'message' => 'Career job status updated successfully',
                'data' => $job->load(['city', 'state', 'creator'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle career job status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active career jobs only
     */
    public function getActiveJobs(Request $request): JsonResponse
    {
        try {
            $query = CareerJob::active()->with(['city', 'state', 'creator']);

            // Apply same filters as index method
            if ($request->has('location_id') && $request->location_id) {
                $query->byLocation($request->location_id);
            }

            if ($request->has('state_id') && $request->state_id) {
                $query->byState($request->state_id);
            }

            if ($request->has('search') && $request->search) {
                $query->where('job_name', 'LIKE', '%' . $request->search . '%');
            }

            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = $request->get('per_page', 15);
            $jobs = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Active career jobs retrieved successfully',
                'data' => $jobs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active career jobs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get jobs statistics
     */
    public function getJobsStats(): JsonResponse
    {
        try {
            $stats = [
                'total_jobs' => CareerJob::count(),
                'active_jobs' => CareerJob::active()->count(),
                'inactive_jobs' => CareerJob::inactive()->count(),
                'jobs_this_month' => CareerJob::whereBetween('created_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth()
                ])->count(),
                'jobs_by_location' => CareerJob::with('city')
                    ->selectRaw('location_id, count(*) as total')
                    ->groupBy('location_id')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'location_id' => $item->location_id,
                            'location_name' => $item->city->name ?? 'Unknown',
                            'total' => $item->total
                        ];
                    })
            ];

            return response()->json([
                'success' => true,
                'message' => 'Job statistics retrieved successfully',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
