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
        'QUANTITY',
        'UNIT_ID',
        'NO_OF_USED',
        'INACTIVE',
        'IS_AUTO'

    ];
}
