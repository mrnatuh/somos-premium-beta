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
        'name',
        'email',
        'password',
        'access',
        'cc',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->access === 'admin';
    }

    public function isManager()
    {
        return $this->access === 'manager';
    }

    public function isDirector()
    {
        return $this->access === 'director';
    }

    public function isCoordinator()
    {
        return $this->access === 'coordinator';
    }

    public function isSupervisor()
    {
        return $this->access === 'user';
    }

    public function level()
    {
        if ($this->isAdmin() || $this->isManager()) {
            return 4;
        } else if ($this->isDirector()) {
            return 3;
        } else if ($this->isCoordinator()) {
            return 2;
        } else {
            return 1;
        }
    }
}
