<?php

namespace App\Modules\FormStudio\Services;

use App\Modules\FormStudio\Models\FormVersion;

class FormBuilderService
{
    /**
     * Parse the JSON schema and map it to the highly relational tables for querying.
     * This is called upon publishing to lock in the structure.
     */
    public function syncSchemaToRelationalTables(FormVersion $version, array $schema)
    {
        // Clear previous relational mappings for this version just in case (though it shouldn't be published twice)
        $version->sections()->delete();

        if (empty($schema['sections'])) {
            return;
        }

        foreach ($schema['sections'] as $sectionIndex => $sectionData) {
            $section = $version->sections()->create([
                'title' => $sectionData['title'] ?? 'Section ' . ($sectionIndex + 1),
                'description' => $sectionData['description'] ?? null,
                'sort_order' => $sectionIndex,
            ]);

            if (!empty($sectionData['fields'])) {
                foreach ($sectionData['fields'] as $fieldIndex => $fieldData) {
                    $field = $section->fields()->create([
                        'type' => $fieldData['type'],
                        'label' => $fieldData['label'],
                        'name' => $fieldData['id'], // use the frontend generated UUID/ID
                        'help_text' => $fieldData['help_text'] ?? null,
                        'is_required' => $fieldData['is_required'] ?? false,
                        'validation_rules' => $fieldData['validation_rules'] ?? null,
                        'sort_order' => $fieldIndex,
                    ]);

                    // Sync Options
                    if (in_array($fieldData['type'], ['select', 'radio', 'checkbox']) && !empty($fieldData['options'])) {
                        foreach ($fieldData['options'] as $optionIndex => $optionData) {
                            $field->options()->create([
                                'label' => $optionData['label'],
                                'value' => $optionData['value'] ?? $optionData['label'],
                                'sort_order' => $optionIndex,
                            ]);
                        }
                    }
                }
            }
        }

        // Note: Conditional logic would be synced here in a second pass once all fields exist
        // to map the target_field_id correctly. (Deferred for Pilot scope).
    }
}
