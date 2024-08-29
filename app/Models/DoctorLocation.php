<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorLocation extends Model
{
    use HasFactory;

    protected $table = 'doctor_location';
    protected $primaryKey = ['LOCATION_ID','DOCTOR_ID'];
    public $timestamps = false;
    protected $fillable = [
        'LOCATION_ID',
        'DOCTOR_ID'
    ];
}
