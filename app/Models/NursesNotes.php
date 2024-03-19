<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NursesNotes extends Model
{
    use HasFactory;

    protected $table = 'nurses_notes';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'HEMO_ID',
        'USER_ID',
        'LINE_NO',
        'TIME',
        'BP_1',
        'BP_2',
        'HR',
        'BFR',
        'AP',
        'VP',
        'TFR',
        'TMP',
        'HEPARIN',
        'FLUSHING',
        'NOTES'

    ];
}
