<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\Events\Controllers\Api\V1\EventController;
use App\Modules\Applications\Controllers\Api\V1\ApplicationController;
use App\Modules\Users\Controllers\Api\V1\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->name('api.v1.')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('events', EventController::class);
    Route::apiResource('applications', ApplicationController::class);
    Route::apiResource('users', UserController::class);

    // Form Studio Builder APIs
    Route::post('form-studio/{form}/draft', [\App\Modules\FormStudio\Controllers\Api\FormBuilderController::class, 'saveDraft'])->name('form-studio.draft');
    Route::post('form-studio/{form}/publish', [\App\Modules\FormStudio\Controllers\Api\FormBuilderController::class, 'publish'])->name('form-studio.publish');
});
