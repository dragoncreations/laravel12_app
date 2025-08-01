<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $fillable = [
        'token',
        'task_id',
        'expires_at',
    ];

    public function setUpdatedAt($value) {}
}
