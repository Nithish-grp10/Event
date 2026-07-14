<?php

namespace App\Modules\FormStudio\Models;

use Illuminate\Database\Eloquent\Model;

class FormFieldOption extends Model
{
    protected $table = 'fs_form_field_options';
    protected $guarded = [];

    public function field()
    {
        return $this->belongsTo(FormField::class, 'field_id');
    }
}
