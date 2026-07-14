<?php

namespace App\Modules\FormStudio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormResponse extends Model
{
    use SoftDeletes;

    protected $table = 'fs_form_responses';
    protected $guarded = [];
    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function version()
    {
        return $this->belongsTo(FormVersion::class, 'form_version_id');
    }

    public function respondent()
    {
        return $this->morphTo();
    }

    public function answers()
    {
        return $this->hasMany(FormAnswer::class, 'response_id');
    }
}
