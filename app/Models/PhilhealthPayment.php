<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhilhealthPayment extends Model
{
    use HasFactory;
    protected $table = 'philhealth_payment';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'PHILHEALTH_ID',
        'RECORDED_ON',
        'RECEIVED_DATE',
        'AMOUNT',
        'REF_NO'
    ];
}
