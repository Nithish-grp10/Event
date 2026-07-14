<?php

namespace App\Modules\Feedback\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    public function event() { return $this->belongsTo(\App\Modules\Events\Models\Event::class); }
    public function application() { return $this->belongsTo(\App\Modules\Applications\Models\Application::class); }
}
