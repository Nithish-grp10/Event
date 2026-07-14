<?php

namespace App\Modules\Applications\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Applications\Models\Application;
use App\Modules\Core\Enums\ApplicationStatus;
use App\Modules\Applications\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApplicationController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Application::with(['event', 'assignee'])->viewableByUser($request->user());

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('applicant_name', 'like', '%' . $request->search . '%')
                  ->orWhere('applicant_email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('event_id') && $request->event_id != '') {
            $query->where('event_id', $request->event_id);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        // Calculate KPIs for the dashboard
        $kpis = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', ApplicationStatus::Submitted->value)->count(),
            'approved' => (clone $query)->where('status', ApplicationStatus::Approved->value)->count(),
            'rejected' => (clone $query)->where('status', ApplicationStatus::Rejected->value)->count(),
        ];

        $applications = $query->latest()->paginate(25)->withQueryString();

        return view('applications.index', compact('applications', 'kpis'));
    }

    public function show(Application $application)
    {
        $this->authorize('view', $application);
        $application->load(['form.fields', 'event', 'activities.user', 'assignee']);

        $assignableUsers = \App\Modules\Users\Models\User::whereHas('roles', function($q) {
            $q->whereIn('name', ['super-admin', 'admin', 'staff', 'organizer']);
        })->get();

        return view('applications.show', compact('application', 'assignableUsers'));
    }

    public function updateStatus(Request $request, Application $application, string $status, ApplicationService $applicationService)
    {
        $this->authorize('update', $application);
        
        $enumStatus = ApplicationStatus::tryFrom($status);
        if (!$enumStatus || !in_array($enumStatus, [ApplicationStatus::Approved, ApplicationStatus::Rejected, ApplicationStatus::Submitted])) {
            abort(400, 'Invalid status.');
        }

        // Update the application status
        $applicationService->updateStatus($application, $enumStatus->value);

        return back()->with('success', "Application status updated to {$enumStatus->label()}.");
    }

    public function destroy(Application $application)
    {
        $this->authorize('delete', $application);
        $application->delete();
        return redirect()->route('applications.index')->with('success', 'Application deleted successfully.');
    }

    public function bulkAction(\App\Http\Requests\BulkApplicationRequest $request, ApplicationService $applicationService)
    {
        $applicationService->handleBulkAction($request->validated('application_ids'), $request->validated('action'));

        return back()->with('success', "Bulk action '{$request->validated('action')}' completed successfully.");
    }

    public function assign(Request $request, Application $application, ApplicationService $applicationService)
    {
        $this->authorize('update', $application);
        $request->validate(['user_id' => 'nullable|exists:users,id']);
        
        $applicationService->assignTo($application, $request->user_id);
        
        return back()->with('success', 'Application assignee updated.');
    }

    public function notes(Request $request, Application $application, ApplicationService $applicationService)
    {
        $this->authorize('update', $application);
        $request->validate(['internal_notes' => 'nullable|string']);
        
        $applicationService->updateNotes($application, $request->internal_notes);
        
        return back()->with('success', 'Internal notes updated.');
    }
}
