<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    use HasFactory;
    protected $table = 'location';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'NAME',
        'INACTIVE',
        'PRICE_LEVEL_ID',
        'GROUP_ID',
        'HCI_MANAGER_ID',
        'PHIC_INCHARGE_ID',
        'PRIMARY_LOGO',
        'SECONDARY_LOGO',
        'NAME_OF_BUSINESS',
        'ACCREDITATION_NO',
        'BLDG_NAME_LOT_BLOCK',
        'STREET_SUB_VALL',
        'BRGY_CITY_MUNI',
        'PROVINCE',        
        'ZIP_CODE',
        'REPORT_HEADER_1',
        'REPORT_HEADER_2',
        'REPORT_HEADER_3'
    ];
}
