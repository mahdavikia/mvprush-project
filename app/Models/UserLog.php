<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'action_name'
    ];
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
