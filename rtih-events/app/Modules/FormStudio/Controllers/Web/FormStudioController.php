<?php

namespace App\Modules\FormStudio\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\FormStudio\Models\Form;
use Illuminate\Http\Request;

class FormStudioController extends Controller
{
    public function index(Request $request)
    {
        $forms = Form::with('latestVersion')->latest()->paginate(15);
        return view('formstudio.index', compact('forms'));
    }

    public function create()
    {
        return view('formstudio.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $form = Form::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'created_by' => $request->user()->id,
            'status' => 'draft',
        ]);

        // Create an initial version
        $form->versions()->create([
            'version_number' => 1,
            'is_published' => false,
            'schema' => [
                'sections' => [],
                'settings' => []
            ]
        ]);

        return redirect()->route('form-studio.builder', $form->id);
    }

    public function builder(Form $form)
    {
        $version = $form->latestVersion;
        if (!$version) {
            $version = $form->versions()->create([
                'version_number' => 1,
                'is_published' => false,
                'schema' => ['sections' => [], 'settings' => []]
            ]);
        }

        // Pass the schema to Alpine for the builder
        $schema = $version->schema ?? ['sections' => []];

        return view('formstudio.builder', compact('form', 'version', 'schema'));
    }
}
