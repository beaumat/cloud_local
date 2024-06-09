<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTreatment extends Model
{
    use HasFactory;
    protected $table = 'item_treatment';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'LOCATION_ID',
        'ITEM_ID',
        'UNIT_ID',
        'NO_OF_USED',
        'INACTIVE'

    ];
}
