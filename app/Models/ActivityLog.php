<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    const UPDATED_AT = null; // log nggak pernah diedit, cukup created_at

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
