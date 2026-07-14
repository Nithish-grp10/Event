<?php

namespace App\Modules\FormStudio\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\FormStudio\Models\Form;
use App\Modules\FormStudio\Services\FormBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormBuilderController extends Controller
{
    public function saveDraft(Request $request, Form $form, FormBuilderService $builderService)
    {
        $request->validate([
            'schema' => 'required|array',
        ]);

        $version = $form->latestVersion;

        if ($version->is_published) {
            // Create a new draft version if the latest is already published
            $version = $form->versions()->create([
                'version_number' => $version->version_number + 1,
                'is_published' => false,
                'schema' => $request->schema,
            ]);
        } else {
            // Update existing draft
            $version->update(['schema' => $request->schema]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Draft saved successfully',
            'version_id' => $version->id,
            'version_number' => $version->version_number
        ]);
    }

    public function publish(Request $request, Form $form, FormBuilderService $builderService)
    {
        $version = $form->latestVersion;
        
        if ($version->is_published) {
            return response()->json(['success' => false, 'message' => 'This version is already published.'], 400);
        }

        DB::transaction(function () use ($version, $form, $request, $builderService) {
            // Mark version as published
            $version->update([
                'is_published' => true,
                'published_by' => $request->user()->id,
                'published_at' => now(),
            ]);

            // Update form status
            $form->update(['status' => 'published']);

            // Parse schema and populate relational tables for querying/reporting
            $builderService->syncSchemaToRelationalTables($version, $version->schema);
        });

        return response()->json([
            'success' => true,
            'message' => 'Form published successfully',
        ]);
    }
}
