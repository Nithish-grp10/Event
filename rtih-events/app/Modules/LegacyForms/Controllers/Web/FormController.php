<?php

namespace App\Modules\LegacyForms\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Modules\LegacyForms\Models\Form;
use App\Modules\LegacyForms\Models\FormField;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FormController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Form::class);
        $forms = Form::latest()->paginate(25);
        return view('forms.index', compact('forms'));
    }

    public function create()
    {
        $this->authorize('create', Form::class);
        return view('forms.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Form::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['created_by'] = $request->user()->id;

        $form = Form::create($validated);

        return redirect()->route('forms.edit', $form)->with('success', 'Form created successfully. You can now add fields.');
    }

    public function show(Form $form)
    {
        $this->authorize('view', $form);
        return view('forms.show', compact('form'));
    }

    public function edit(Form $form)
    {
        $this->authorize('update', $form);
        $form->load(['fields' => function ($query) {
            $query->orderBy('sort_order');
        }]);
        
        return view('forms.edit', compact('form'));
    }

    public function update(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'nullable|array',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.field_type' => 'required|string|in:text,email,textarea,select,checkbox,radio,file',
            'fields.*.options' => 'nullable|string',
            'fields.*.is_required' => 'nullable|boolean',
            'fields.*.sort_order' => 'required|integer',
        ]);

        $form->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->has('fields')) {
            $form->fields()->delete();
            $fieldsToInsert = [];
            foreach ($validated['fields'] as $field) {
                $optionsArray = null;
                if (in_array($field['field_type'], ['select', 'radio', 'checkbox']) && !empty($field['options'])) {
                    // Split by new line, trim, and filter empty
                    $optionsArray = array_values(array_filter(array_map('trim', explode("\n", $field['options']))));
                }

                $fieldsToInsert[] = [
                    'form_id' => $form->id,
                    'label' => $field['label'],
                    'field_type' => $field['field_type'],
                    'options' => $optionsArray ? json_encode($optionsArray) : null,
                    'is_required' => $field['is_required'] ?? false,
                    'sort_order' => $field['sort_order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($fieldsToInsert)) {
                FormField::insert($fieldsToInsert);
            }
        } else {
            // If fields is completely empty/missing but they submitted the form
            // we should technically delete them all, or it might just be a metadata update.
            // Based on step 6, they submit the whole array.
            $form->fields()->delete();
        }

        return redirect()->route('forms.edit', $form)->with('success', 'Form updated successfully.');
    }

    public function destroy(Form $form)
    {
        $this->authorize('delete', $form);
        
        $hasPublishedEvents = $form->events()->where('status', 'published')->exists();
        
        if ($hasPublishedEvents) {
            return back()->withErrors(['destroy' => 'Cannot delete form because it is attached to a published event.']);
        }

        $form->delete();
        
        return redirect()->route('forms.index')->with('success', 'Form deleted successfully.');
    }

    public function clone(Form $form)
    {
        $this->authorize('create', Form::class);
        
        $newForm = $form->replicate();
        $newForm->title = $newForm->title . ' (Copy)';
        $newForm->created_by = auth()->id();
        $newForm->save();

        foreach ($form->fields as $field) {
            $newField = $field->replicate();
            $newField->form_id = $newForm->id;
            $newField->save();
        }

        return redirect()->route('forms.edit', $newForm)->with('success', 'Form cloned successfully.');
    }
}
