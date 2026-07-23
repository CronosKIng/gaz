<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // Table name in database
    protected $table = 'student';
    
    // Primary key
    protected $primaryKey = 'sid';

    // Fields that can be filled
    protected $fillable = [
        'reg',
        'sname',
        'address',
        'dob',
        'gender',
        'religion',
        'national',
        'school',
        'pgname',
        'pgaddress',
        'pgmob',
        'relation',
        'spname',
        'spaddress',
        'spmob',
        'accupation',
        'level',
        'class',
        'shehia',
        'date',
        'ward',
        'trans',
        'building',
        'room',
        'bed',
        'status',
        'year',
        'tayar',
        'staff',
        'name',
        'type',
        'size',
        'data',
        'password',
        'plain_password',
        'usafiri'
    ];

    // Disable timestamps if not in table
    public $timestamps = false;
}
