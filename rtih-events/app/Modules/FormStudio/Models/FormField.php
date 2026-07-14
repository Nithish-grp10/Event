<?php

namespace App\Modules\FormStudio\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    protected $table = 'fs_form_fields';
    protected $guarded = [];
    protected $casts = [
        'is_required' => 'boolean',
        'validation_rules' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(FormSection::class, 'section_id');
    }

    public function options()
    {
        return $this->hasMany(FormFieldOption::class, 'field_id')->orderBy('sort_order');
    }

    public function conditions()
    {
        return $this->hasMany(FormCondition::class, 'field_id');
    }
}
