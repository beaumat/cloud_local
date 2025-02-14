<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhicAgreementFormDetails extends Model
{
    use HasFactory;

    protected $table = 'PHIC_AGREEMENT_FORM_DETAILS';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'HEMO_ID',
        'PHIC_AFT_ID',
        'IS_CHECK'
    ];
}
