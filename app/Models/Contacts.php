<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;

    protected $table = 'contact';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        "ID",
        "TYPE",
        "NAME",
        "COMPANY_NAME",
        "SALUTATION",
        "FIRST_NAME",
        "MIDDLE_NAME",
        "LAST_NAME",
        "PRINT_NAME_AS",
        "POSTAL_ADDRESS",
        "CONTACT_PERSON",
        "TELEPHONE_NO",
        "FAX_NO",
        "MOBILE_NO",
        "ALT_TELEPHONE_NO",
        "ALT_CONTACT_PERSON",
        "EMAIL",
        "ACCOUNT_NO",
        "INACTIVE",
        "GROUP_ID",
        "PAYMENT_TERMS_ID",
        "CREDIT_LIMIT",
        "PREF_PAYMENT_METHOD_ID",
        "CREDIT_CARD_NO",
        "CREDIT_CARD_EXPIRY_DATE",
        "SALES_REP_ID",
        "PRICE_LEVEL_ID",
        "APPLY_FINANCE_CHARGE",
        "TAXPAYER_ID",
        "TAX_ID",
        "EW_TAX_ID",
        "SSS_NO",
        "GENDER",
        "DATE_OF_BIRTH",
        "NICKNAME",
        "HIRE_DATE",
        "CUSTOM_FIELD1",
        "CUSTOM_FIELD2",
        "CUSTOM_FIELD3",
        "CUSTOM_FIELD4",
        "CUSTOM_FIELD5",
        "SCHEDULE_TYPE",
        "FIX_MON",
        "FIX_TUE",
        "FIX_WEN",
        "FIX_THU",
        "FIX_FRI",
        "FIX_SAT",
        "FIX_SUN",
        "LOCATION_ID",
        "PATIENT_TYPE_ID",
        "PATIENT_STATUS_ID",
        "ADMITTED",
        "LONG_HRS_DURATION",
        "DATE_ADMISSION"
    ];
}
