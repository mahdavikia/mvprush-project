<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'activated',
        'role_id',
        'team_id'
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function content_cats()
    {
        return $this->belongsTo(ContentCat::class);
    }
    public function contents()
    {
        return $this->belongsTo(Content::class);
    }
    public function product_version()
    {
        return $this->belongsTo(ProductVersion::class);
    }
}
