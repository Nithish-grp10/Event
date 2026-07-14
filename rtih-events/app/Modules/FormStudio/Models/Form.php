<?php

namespace App\Modules\FormStudio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use SoftDeletes;

    protected $table = 'fs_forms';
    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(\App\Modules\Users\Models\User::class, 'created_by');
    }

    public function versions()
    {
        return $this->hasMany(FormVersion::class, 'form_id');
    }

    public function latestVersion()
    {
        return $this->hasOne(FormVersion::class, 'form_id')->latestOfMany('version_number');
    }

    public function publishedVersion()
    {
        return $this->hasOne(FormVersion::class, 'form_id')->where('is_published', true)->latestOfMany('version_number');
    }
}
