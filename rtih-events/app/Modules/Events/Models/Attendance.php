<?php

namespace App\Modules\Events\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = [];

    public function event() { return $this->belongsTo(Event::class); }
    public function application() { return $this->belongsTo(Application::class); }
    public function scanner() { return $this->belongsTo(User::class, 'scanned_by'); }
}
