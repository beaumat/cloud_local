<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhicAgreementFormTitle extends Model
{
    use HasFactory;

    protected $table = 'PHIC_AGREEMENT_FORM_TITLE';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'TYPE',
        'LINE',
        'DESCRIPTION'
    ];
}
