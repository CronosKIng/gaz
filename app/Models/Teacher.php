<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'staff';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user',
        'password',
        'name',
        'contact',
        'status'
    ];

    protected $hidden = [
        'password',
    ];

    // Override the getAuthIdentifierName method
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    // Override the getAuthPassword method
    public function getAuthPassword()
    {
        return $this->password;
    }
}
