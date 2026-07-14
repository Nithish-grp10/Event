<?php

namespace App\Modules\Applications\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Users\Models\User;

class ApplicationActivity extends Model
{
    protected $guarded = [];
    protected $casts = ['metadata' => 'array'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
