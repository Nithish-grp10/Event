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
        $application->update(['status' => $status]);

        $this->sendStatusEmail($application, $status);

        return $application;
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
            } elseif ($action === 'delete') {
                $this->authorize('delete', $app);
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
        } elseif ($status === 'rejected') {
            Mail::to($application->applicant_email)
                ->queue(new ApplicationRejected($application));
        }
    }
}
