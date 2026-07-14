<?php

namespace App\Modules\FormStudio\Models;

use Illuminate\Database\Eloquent\Model;

class FormVersion extends Model
{
    protected $table = 'fs_form_versions';
    protected $guarded = [];
    protected $casts = [
        'is_published' => 'boolean',
        'schema' => 'array',
        'published_at' => 'datetime',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function sections()
    {
        return $this->hasMany(FormSection::class, 'version_id')->orderBy('sort_order');
    }

    public function publisher()
    {
        return $this->belongsTo(\App\Modules\Users\Models\User::class, 'published_by');
    }
}
