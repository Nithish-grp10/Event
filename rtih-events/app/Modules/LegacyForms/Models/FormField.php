<?php

namespace App\Modules\LegacyForms\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
