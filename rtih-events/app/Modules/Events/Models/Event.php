<?php

namespace App\Modules\Events\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function forms()
    {
        return $this->belongsToMany(Form::class, 'event_forms');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'event_staff');
    }

    /**
     * Scope a query to only include events viewable by the given user.
     */
    public function scopeViewableByUser($query, \App\Modules\Users\Models\User $user)
    {
        if ($user->hasRole('super-admin')) {
            return $query;
        }

        if ($user->hasRole('admin')) {
            return $query->where('created_by', $user->id);
        }

        if ($user->hasRole('staff')) {
            return $query->whereHas('staff', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        return $query->where('id', 0); // Deny by default
    }
}
