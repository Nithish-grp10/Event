<?php

namespace App\Modules\LegacyForms\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function fields()
    {
        return $this->hasMany(FormField::class)->orderBy('sort_order');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_forms');
    }
}
