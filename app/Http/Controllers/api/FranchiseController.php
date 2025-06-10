<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController;
use App\Model\Franchise;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class FranchiseController extends BaseController
{
    /**
     * Display a listing of franchise applications with filters
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Franchise::query();

            // Apply filters
            if ($request->has('state') && $request->state) {
                $query->byState($request->state);
            }

            if ($request->has('district') && $request->district) {
                $query->byDistrict($request->district);
            }

            if ($request->has('taluk') && $request->taluk) {
                $query->byTaluk($request->taluk);
            }

            if ($request->has('pincode') && $request->pincode) {
                $query->byPincode($request->pincode);
            }

            // Date filters
            if ($request->has('start_date') || $request->has('end_date')) {
                $query->dateRange($request->start_date, $request->end_date);
            }

            // Search filter
            if ($request->has('search') && $request->search) {
                $query->search($request->search);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            // $perPage = $request->get('per_page', 15);
            // $franchises = $query->paginate($perPage);
            $franchises = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Franchise applications retrieved successfully',
                'data' => $franchises
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve franchise applications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created franchise application
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|string',
                'name' => 'required|string|max:255',
                'phone_1' => 'required|string|max:255',
                'phone_2' => 'nullable|string|max:255',
                'email_id' => 'required|email|max:255',
                'shop_location_plan' => 'required|string',
                'message' => 'nullable|string',
                'address' => 'required|string',
                'pincode' => 'required|string|max:255',
                'taluk' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'state' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // $franchise = Franchise::create($validator->validated());

            if (!empty($validator['id'])) {
                // Update
                $franchise = Franchise::find($validator['id']);

                if ($franchise) {
                    $franchise->update($validator);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Franchise not found',
                    ], 404);
                }
            } else {
                // Create new
                $franchise = Franchise::create($validator);
            }

            return response()->json([
                'success' => true,
                'message' => 'Franchise application submitted successfully',
                'data' => $franchise
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit franchise application',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified franchise application
     */
    public function show($id): JsonResponse
    {
        try {
            $franchise = Franchise::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Franchise application retrieved successfully',
                'data' => $franchise
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Franchise application not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified franchise application
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $franchise = Franchise::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'phone_1' => 'sometimes|required|string|max:255',
                'phone_2' => 'nullable|string|max:255',
                'email_id' => 'sometimes|required|email|max:255',
                'shop_location_plan' => 'sometimes|required|string',
                'message' => 'nullable|string',
                'address' => 'sometimes|required|string',
                'pincode' => 'sometimes|required|string|max:255',
                'taluk' => 'sometimes|required|string|max:255',
                'district' => 'sometimes|required|string|max:255',
                'state' => 'sometimes|required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $franchise->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Franchise application updated successfully',
                'data' => $franchise
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update franchise application',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified franchise application
     */
    public function destroy($id): JsonResponse
    {
        try {
            $franchise = Franchise::findOrFail($id);
            $franchise->delete();

            return response()->json([
                'success' => true,
                'message' => 'Franchise application deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete franchise application',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get franchise statistics
     */
    public function getFranchiseStats(): JsonResponse
    {
        try {
            $stats = [
                'total_applications' => Franchise::count(),
                'applications_this_month' => Franchise::whereBetween('created_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth()
                ])->count(),
                'applications_by_state' => Franchise::selectRaw('state, count(*) as total')
                    ->groupBy('state')
                    ->orderBy('total', 'desc')
                    ->get(),
                'applications_by_district' => Franchise::selectRaw('district, state, count(*) as total')
                    ->groupBy(['district', 'state'])
                    ->orderBy('total', 'desc')
                    ->limit(10)
                    ->get(),
                'recent_applications' => Franchise::latest()
                    ->limit(5)
                    ->get(['id', 'name', 'district', 'state', 'created_at'])
            ];

            return response()->json([
                'success' => true,
                'message' => 'Franchise statistics retrieved successfully',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve franchise statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
