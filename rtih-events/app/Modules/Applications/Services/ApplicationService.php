<?php

namespace App\Modules\Applications\Services;

use App\Modules\Applications\Models\Application;
use App\Mail\ApplicationApproved;
use App\Mail\ApplicationRejected;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApplicationService
{
    use AuthorizesRequests;

    /**
     * Update the status of a single application.
     */
    public function updateStatus(Application $application, string $status): Application
    {
        $oldStatus = $application->status;
        $application->update(['status' => $status]);

        $this->sendStatusEmail($application, $status);
        
        $this->logActivity($application, 'status_changed', "Status changed from {$oldStatus} to {$status}", [
            'old_status' => $oldStatus,
            'new_status' => $status
        ]);

        return $application;
    }

    /**
     * Update internal notes.
     */
    public function updateNotes(Application $application, ?string $notes): void
    {
        $application->update(['internal_notes' => $notes]);
        $this->logActivity($application, 'note_updated', 'Internal notes updated');
    }

    /**
     * Assign application to a user.
     */
    public function assignTo(Application $application, ?int $userId): void
    {
        $application->update(['assigned_to' => $userId]);
        
        $assigneeName = $userId ? \App\Modules\Users\Models\User::find($userId)?->name : 'Unassigned';
        $this->logActivity($application, 'assigned', "Assigned to {$assigneeName}", ['assigned_to' => $userId]);
    }

    /**
     * Handle bulk actions for applications.
     */
    public function handleBulkAction(array $applicationIds, string $action): void
    {
        $applications = Application::whereIn('id', $applicationIds)->get();

        foreach ($applications as $app) {
            $this->authorize('update', $app);
            
            if ($action === 'approve') {
                $this->updateStatus($app, 'approved');
            } elseif ($action === 'reject') {
                $this->updateStatus($app, 'rejected');
            } elseif ($action === 'email') {
                $this->logActivity($app, 'email_sent', 'Bulk email sent to applicant');
                // Email logic would go here
            } elseif ($action === 'delete') {
                $this->authorize('delete', $app);
                $this->logActivity($app, 'deleted', "Application deleted");
                $app->delete();
            }
        }
    }

    /**
     * Send status update email if applicable.
     */
    protected function sendStatusEmail(Application $application, string $status): void
    {
        if ($status === 'approved') {
            Mail::to($application->applicant_email)
                ->queue(new ApplicationApproved($application));
            $this->logActivity($application, 'email_sent', 'Approval email sent to applicant');
        } elseif ($status === 'rejected') {
            Mail::to($application->applicant_email)
                ->queue(new ApplicationRejected($application));
            $this->logActivity($application, 'email_sent', 'Rejection email sent to applicant');
        }
    }

    /**
     * Log an activity on the application timeline.
     */
    public function logActivity(Application $application, string $type, string $description, array $metadata = []): void
    {
        $application->activities()->create([
            'user_id' => auth()->id(),
            'type' => $type,
            'description' => $description,
            'metadata' => $metadata
        ]);
    }
}
