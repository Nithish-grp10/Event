<?php

namespace App\Modules\FormStudio\Models;

use Illuminate\Database\Eloquent\Model;

class FormSection extends Model
{
    protected $table = 'fs_form_sections';
    protected $guarded = [];

    public function version()
    {
        return $this->belongsTo(FormVersion::class, 'version_id');
    }

    public function fields()
    {
        return $this->hasMany(FormField::class, 'section_id')->orderBy('sort_order');
    }
}
