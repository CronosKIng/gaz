<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    // Tumia table ya 'student_app' badala ya 'admissions'
    protected $table = 'student_app';
    
    // Primary key
    protected $primaryKey = 'id';

    public $timestamps = false;

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
}
