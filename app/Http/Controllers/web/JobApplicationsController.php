<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\CareerJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class JobApplicationController extends Controller
{
    /**
     * Display a listing of job applications with filters
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = JobApplication::with(['careerJob', 'careerJob.city', 'careerJob.creator']);

            // Apply filters
            if ($request->has('career_job_id') && $request->career_job_id) {
                $query->byCareerJob($request->career_job_id);
            }

            // Date filters
            if ($request->has('start_date') || $request->has('end_date')) {
                $query->dateRange($request->start_date, $request->end_date);
            }

            // Search filter
            if ($request->has('search') && $request->search) {
                $query->search($request->search);
            }

            // Filter by active jobs only
            if ($request->get('active_jobs_only', false)) {
                $query->withActiveJobs();
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $applications = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Job applications retrieved successfully',
                'data' => $applications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job applications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created job application (Apply for job)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'contact_number' => 'required|string|max:255',
                'attach_resume' => 'nullable|file|mimes:pdf|max:5120', // 5MB max
                'career_job_id' => 'required|exists:career_jobs,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if the job is active
            $careerJob = CareerJob::findOrFail($request->career_job_id);
            if ($careerJob->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'This job is no longer accepting applications'
                ], 400);
            }

            $data = $validator->validated();

            // Handle file upload
            if ($request->hasFile('attach_resume')) {
                $file = $request->file('attach_resume');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('resumes', $filename, 'public');
                $data['attach_resume'] = $path;
            }

            $application = JobApplication::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Job application submitted successfully',
                'data' => $application->load(['careerJob'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit job application',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified job application
     */
    public function show($id): JsonResponse
    {
        try {
            $application = JobApplication::with(['careerJob', 'careerJob.city', 'careerJob.creator'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Job application retrieved successfully',
                'data' => $application
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job application not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified job application
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $application = JobApplication::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|max:255',
                'contact_number' => 'sometimes|required|string|max:255',
                'attach_resume' => 'nullable|file|mimes:pdf|max:5120', // 5MB max
                'career_job_id' => 'sometimes|required|exists:career_jobs,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle file upload
            if ($request->hasFile('attach_resume')) {
                // Delete old resume if exists
                if ($application->attach_resume) {
                    Storage::disk('public')->delete($application->attach_resume);
                }

                $file = $request->file('attach_resume');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('resumes', $filename, 'public');
                $data['attach_resume'] = $path;
            }

            $application->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Job application updated successfully',
                'data' => $application->load(['careerJob'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update job application',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified job application
     */
    public function destroy($id): JsonResponse
    {
        try {
            $application = JobApplication::findOrFail($id);

            // Delete resume file if exists
            if ($application->attach_resume) {
                Storage::disk('public')->delete($application->attach_resume);
            }

            $application->delete();

            return response()->json([
                'success' => true,
                'message' => 'Job application deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete job application',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get applications for a specific job
     */
    public function getApplicationsByJob($careerJobId, Request $request): JsonResponse
    {
        try {
            $query = JobApplication::with(['careerJob'])
                ->byCareerJob($careerJobId);

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
            $applications = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Job applications retrieved successfully',
                'data' => $applications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job applications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active jobs for application form
     */
    public function getActiveJobs(Request $request): JsonResponse
    {
        try {
            $query = CareerJob::active()->with(['city', 'state']);

            // Apply location filters if needed
            if ($request->has('location_id') && $request->location_id) {
                $query->byLocation($request->location_id);
            }

            if ($request->has('state_id') && $request->state_id) {
                $query->byState($request->state_id);
            }

            $jobs = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Active jobs retrieved successfully',
                'data' => $jobs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active jobs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get job application statistics
     */
    public function getApplicationStats(): JsonResponse
    {
        try {
            $stats = [
                'total_applications' => JobApplication::count(),
                'applications_this_month' => JobApplication::whereBetween('created_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth()
                ])->count(),
                'applications_this_week' => JobApplication::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'applications_today' => JobApplication::whereDate('created_at', now())->count(),
                'total_active_jobs' => CareerJob::where('status', 'active')->count(),
                'most_applied_jobs' => JobApplication::select('career_job_id', DB::raw('count(*) as applications_count'))
                    ->with(['careerJob:id,title'])
                    ->groupBy('career_job_id')
                    ->orderBy('applications_count', 'desc')
                    ->limit(5)
                    ->get(),
                'recent_applications' => JobApplication::with(['careerJob:id,title'])
                    ->latest()
                    ->limit(5)
                    ->get(['id', 'name', 'email', 'career_job_id', 'created_at']),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Application statistics retrieved successfully',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve application statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download resume file
     */
    public function downloadResume($id): JsonResponse|BinaryFileResponse
    {
        try {
            $application = JobApplication::findOrFail($id);

            if (!$application->attach_resume) {
                return response()->json([
                    'success' => false,
                    'message' => 'No resume found for this application'
                ], 404);
            }

            $filePath = storage_path('app/public/' . $application->attach_resume);

            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resume file not found on server'
                ], 404);
            }

            return response()->download($filePath);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download resume',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete job applications
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array|min:1',
                'ids.*' => 'required|integer|exists:job_applications,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $applications = JobApplication::whereIn('id', $request->ids)->get();

            // Delete associated resume files
            foreach ($applications as $application) {
                if ($application->attach_resume) {
                    Storage::disk('public')->delete($application->attach_resume);
                }
            }

            // Delete applications
            JobApplication::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' job applications deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete job applications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export job applications to CSV
     */
    public function exportApplications(Request $request): JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
    {
        try {
            $query = JobApplication::with(['careerJob']);

            // Apply filters if provided
            if ($request->has('career_job_id') && $request->career_job_id) {
                $query->byCareerJob($request->career_job_id);
            }

            if ($request->has('start_date') || $request->has('end_date')) {
                $query->dateRange($request->start_date, $request->end_date);
            }

            $applications = $query->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="job_applications_' . now()->format('Y-m-d') . '.csv"',
            ];

            $callback = function () use ($applications) {
                $file = fopen('php://output', 'w');

                // CSV Headers
                fputcsv($file, [
                    'ID',
                    'Name',
                    'Email',
                    'Contact Number',
                    'Job Title',
                    'Applied Date',
                    'Resume'
                ]);

                // CSV Data
                foreach ($applications as $application) {
                    fputcsv($file, [
                        $application->id,
                        $application->name,
                        $application->email,
                        $application->contact_number,
                        $application->careerJob->title ?? 'N/A',
                        $application->created_at->format('Y-m-d H:i:s'),
                        $application->attach_resume ? 'Yes' : 'No'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export applications',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
