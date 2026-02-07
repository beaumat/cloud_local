<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankStatement extends Model
{
    use HasFactory;
    protected $table      = 'bank_statement';
    protected $primaryKey = 'ID';
    public $timestamps    = false;
    protected $fillable   = [
        'ID',
        'RECORDED_ON',
        'DATE',
        'FILE_NAME',
        'FILE_PATH',
        'DESCRIPTION',
        'BANK_ACCOUNT_ID',
        'FILE_TYPE',
        'NOTES',
        'RECON_STATUS',
        'RECON_DATE',
    ];
}
