<?php

namespace App\Modules\FormStudio\Models;

use Illuminate\Database\Eloquent\Model;

class FormCondition extends Model
{
    protected $table = 'fs_form_conditions';
    protected $guarded = [];

    public function field()
    {
        return $this->belongsTo(FormField::class, 'field_id');
    }

    public function targetField()
    {
        return $this->belongsTo(FormField::class, 'target_field_id');
    }
}
