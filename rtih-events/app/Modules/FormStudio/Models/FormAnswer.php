<?php

namespace App\Modules\FormStudio\Models;

use Illuminate\Database\Eloquent\Model;

class FormAnswer extends Model
{
    protected $table = 'fs_form_answers';
    protected $guarded = [];
    protected $casts = [
        'value' => 'array', // JSON cast
    ];

    public function response()
    {
        return $this->belongsTo(FormResponse::class, 'response_id');
    }

    public function field()
    {
        return $this->belongsTo(FormField::class, 'field_id');
    }
}
