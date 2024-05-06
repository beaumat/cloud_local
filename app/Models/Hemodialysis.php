<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hemodialysis extends Model
{
    use HasFactory;

    protected $table = 'hemodialysis';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'DATE',
        'CODE',
        'CUSTOMER_ID',
        'LOCATION_ID',
        'USER_ID',
        'NO_OF_TREATMENT',
        'MACHINE_NO',
        'PRE_WEIGHT',
        'PRE_BLOOD_PRESSURE',
        'PRE_HEART_RATE',
        'PRE_O2_SATURATION',
        'PRE_TEMPERATURE',
        'POST_WEIGHT',
        'POST_BLOOD_PRESSURE',
        'POST_HEART_RATE',
        'POST_O2_SATURATION',
        'POST_TEMPERATURE',
        'STATUS_ID',
        'STATUS_DATE',
        'TIME_START',
        'TIME_END',
        'PRE_BLOOD_PRESSURE2',
        'POST_BLOOD_PRESSURE2',
        'FILE_NAME',
        'FILE_PATH'
    ];
}
