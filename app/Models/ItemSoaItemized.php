<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSoaItemized extends Model
{
    use HasFactory;
    protected $table = 'ItemSoaItemized';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'DESCRIPTION'
    ];

}
