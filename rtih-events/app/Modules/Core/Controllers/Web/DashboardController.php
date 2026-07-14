<?php

namespace App\Modules\Core\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Modules\Events\Models\Event;
use App\Modules\Applications\Models\Application;
use App\Modules\LegacyForms\Models\Form;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Base Queries based on role
        $eventsQuery = Event::query();
        $appsQuery = Application::query();

        if ($user->hasRole('admin')) {
            $eventsQuery->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_admin_id', $user->id);
            });
            $appsQuery->whereHas('event', function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_admin_id', $user->id);
            });
        } elseif ($user->hasRole('staff')) {
            $eventsQuery->whereHas('staff', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            $appsQuery->whereHas('event.staff', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $stats = [
            'total_events' => (clone $eventsQuery)->count(),
            'published_events' => (clone $eventsQuery)->where('status', 'published')->count(),
            'total_applications' => (clone $appsQuery)->count(),
            'approved_applications' => (clone $appsQuery)->where('status', 'approved')->count(),
            'rejected_applications' => (clone $appsQuery)->where('status', 'rejected')->count(),
            'total_forms' => Form::count(),
        ];

        // Chart Data: Applications per month for the last 6 months
        $months = collect();
        $appCounts = collect();
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push($date->format('M'));
            $count = (clone $appsQuery)->whereYear('created_at', $date->year)
                                       ->whereMonth('created_at', $date->month)
                                       ->count();
            $appCounts->push($count);
        }

        $chartData = [
            'labels' => $months->toArray(),
            'data' => $appCounts->toArray(),
        ];

        return view('dashboard.index', compact('stats', 'chartData'));
    }
}
