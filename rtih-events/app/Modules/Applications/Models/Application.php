<?php

namespace App\Modules\Applications\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Events\Models\Event;

use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];
    protected $casts = ['data' => 'array'];

    public function event() { return $this->belongsTo(Event::class); }
    public function form() { return $this->belongsTo(Form::class); }
    public function attendance() { return $this->hasOne(Attendance::class); }

    /**
     * Scope a query to only include applications viewable by the given user.
     */
    public function scopeViewableByUser($query, \App\Modules\Users\Models\User $user)
    {
        if ($user->hasRole('super-admin')) {
            return $query;
        }

        if ($user->hasRole('admin')) {
            return $query->whereHas('event', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        if ($user->hasRole('staff')) {
            return $query->whereHas('event', function ($q) use ($user) {
                $q->whereHas('staff', function ($sq) use ($user) {
                    $sq->where('user_id', $user->id);
                });
            });
        }

        return $query->where('id', 0); // Deny by default
    }
}
