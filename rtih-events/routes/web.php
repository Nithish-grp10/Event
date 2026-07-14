<?php

use App\Modules\Users\Controllers\Web\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Modules\Core\Controllers\Web\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/events/{event:slug}/apply', [App\Modules\Applications\Controllers\Web\PublicApplicationController::class, 'show'])->name('public.apply.show');
Route::post('/events/{event:slug}/apply', [App\Modules\Applications\Controllers\Web\PublicApplicationController::class, 'store'])->name('public.apply.store');
Route::get('/apply/confirmation/{application}', [App\Modules\Applications\Controllers\Web\PublicApplicationController::class, 'confirmation'])->name('public.apply.confirmation');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('events', App\Modules\Events\Controllers\Web\EventController::class);
    Route::post('events/{event}/publish', [App\Modules\Events\Controllers\Web\EventController::class, 'publish'])->name('events.publish');
    Route::post('events/{event}/unpublish', [App\Modules\Events\Controllers\Web\EventController::class, 'unpublish'])->name('events.unpublish');
    Route::post('events/{event}/duplicate', [App\Modules\Events\Controllers\Web\EventController::class, 'duplicate'])->name('events.duplicate');
    Route::post('events/{event}/status/{status}', [App\Modules\Events\Controllers\Web\EventController::class, 'updateStatus'])->name('events.status');
    
    Route::resource('forms', App\Modules\LegacyForms\Controllers\Web\FormController::class);
    Route::post('forms/{form}/clone', [App\Modules\LegacyForms\Controllers\Web\FormController::class, 'clone'])->name('forms.clone');

    Route::resource('applications', App\Modules\Applications\Controllers\ApplicationController::class)->only(['index', 'show', 'destroy']);
    Route::post('applications/bulk', [App\Modules\Applications\Controllers\ApplicationController::class, 'bulkAction'])->name('applications.bulk');
    Route::post('applications/{application}/status/{status}', [App\Modules\Applications\Controllers\ApplicationController::class, 'updateStatus'])->name('applications.status');

    Route::get('events/{event}/attendance/scan', [App\Modules\Events\Controllers\Web\AttendanceController::class, 'scan'])->name('events.attendance.scan');
    Route::post('events/{event}/attendance/scan', [App\Modules\Events\Controllers\Web\AttendanceController::class, 'store'])->name('events.attendance.store');

    Route::get('feedbacks', [App\Modules\Feedback\Controllers\Web\FeedbackController::class, 'index'])->name('feedback.index');

    Route::resource('admins', App\Modules\Users\Controllers\Web\AdminController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::post('notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllRead');

    Route::get('applications/export', [App\Modules\Core\Controllers\Web\ExportController::class, 'exportApplications'])->name('applications.export');
    Route::get('search', [App\Modules\Core\Controllers\Web\SearchController::class, 'search'])->name('search');

    // Form Studio Routes
    Route::get('form-studio', [App\Modules\FormStudio\Controllers\Web\FormStudioController::class, 'index'])->name('form-studio.index');
    Route::get('form-studio/create', [App\Modules\FormStudio\Controllers\Web\FormStudioController::class, 'create'])->name('form-studio.create');
    Route::post('form-studio', [App\Modules\FormStudio\Controllers\Web\FormStudioController::class, 'store'])->name('form-studio.store');
    Route::get('form-studio/{form}/builder', [App\Modules\FormStudio\Controllers\Web\FormStudioController::class, 'builder'])->name('form-studio.builder');
    
    // Form Studio AJAX endpoints
    Route::post('form-studio/{form}/draft', [App\Modules\FormStudio\Controllers\Api\FormBuilderController::class, 'saveDraft'])->name('form-studio.draft');
    Route::post('form-studio/{form}/publish', [App\Modules\FormStudio\Controllers\Api\FormBuilderController::class, 'publish'])->name('form-studio.publish');
});

// Public Feedback Routes
Route::get('e/{event:slug}/feedback', [App\Modules\Feedback\Controllers\Web\FeedbackController::class, 'create'])->name('public.feedback.create');
Route::post('e/{event:slug}/feedback', [App\Modules\Feedback\Controllers\Web\FeedbackController::class, 'store'])->name('public.feedback.store');
Route::get('feedback/thanks', function () {
    return view('public.feedback-thanks');
})->name('public.feedback.thanks');

require __DIR__.'/auth.php';
