<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController;
use App\Model\BulkEnquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BulkEnquiryController extends BaseController
{
    /**
     * Display a listing of bulk enquiries with filters
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = BulkEnquiry::query();

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
            $perPage = $request->get('per_page', 15);
            $franchises = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Bulk enquiries retrieved successfully',
                'data' => $franchises
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bulk enquiries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created bulk enquiry
     */
    public function store(Request $request)
    {
        try {
            $validator = $request->validate([
                'name' => 'required|string|max:255',
                'phone_1' => 'required|string|max:255',
                'phone_2' => 'nullable|string|max:255',
                'email_id' => 'required|email|max:255',
                'message' => 'nullable|string',
                'address' => 'required|string',
                'pincode' => 'required|string|max:255',
                'taluk' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'product_id' => 'required|int|exists:food_list,id',
                'business_category_id' => 'required|int|exists:business_category,id',
            ]);

            $bulkEnquiry = BulkEnquiry::create($validator);

            return response()->json([
                'success' => true,
                'message' => 'Bulk enquiry submitted successfully',
                'data' => $bulkEnquiry
            ], 201);
        } catch (\Exception $e) {           
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit bulk enquiry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified bulk enquiry
     */
    public function show($id): JsonResponse
    {
        try {
            $bulkEnquiry = BulkEnquiry::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Bulk enquiry retrieved successfully',
                'data' => $bulkEnquiry
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk enquiry not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified bulk enquiry
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $bulkEnquiry = BulkEnquiry::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'phone_1' => 'sometimes|required|string|max:255',
                'phone_2' => 'nullable|string|max:255',
                'email_id' => 'sometimes|required|email|max:255',
                'message' => 'nullable|string',
                'address' => 'sometimes|required|string',
                'pincode' => 'sometimes|required|string|max:255',
                'taluk' => 'sometimes|required|string|max:255',
                'district' => 'sometimes|required|string|max:255',
                'state' => 'sometimes|required|string|max:255',
                'product_id' => 'required|int|exists:food_list,id',
                'business_category_id' => 'required|int|exists:business_category,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $bulkEnquiry->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Bulk enquiry updated successfully',
                'data' => $bulkEnquiry
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update bulk enquiry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified bulk enquiry
     */
    public function destroy($id): JsonResponse
    {
        try {
            $bulkEnquiry = BulkEnquiry::findOrFail($id);
            $bulkEnquiry->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bulk enquiry deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete bulk enquiry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get franchise statistics
     */
    // public function getFranchiseStats(): JsonResponse
    // {
    //     try {
    //         $stats = [
    //             'total_applications' => Franchise::count(),
    //             'applications_this_month' => Franchise::whereBetween('created_at', [
    //                 now()->startOfMonth(),
    //                 now()->endOfMonth()
    //             ])->count(),
    //             'applications_by_state' => Franchise::selectRaw('state, count(*) as total')
    //                 ->groupBy('state')
    //                 ->orderBy('total', 'desc')
    //                 ->get(),
    //             'applications_by_district' => Franchise::selectRaw('district, state, count(*) as total')
    //                 ->groupBy(['district', 'state'])
    //                 ->orderBy('total', 'desc')
    //                 ->limit(10)
    //                 ->get(),
    //             'recent_applications' => Franchise::latest()
    //                 ->limit(5)
    //                 ->get(['id', 'name', 'district', 'state', 'created_at'])
    //         ];

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Franchise statistics retrieved successfully',
    //             'data' => $stats
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to retrieve franchise statistics',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
