<?php

namespace App\Livewire\Patient;

use App\Models\ContactGroup;
use App\Models\Contacts;
use App\Models\PaymentMethods;
use App\Models\PaymentTerms;
use App\Models\PriceLevels;
use App\Models\Tax;
use App\Services\ContactServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Patients')]
class PatientForm extends Component
{

    public int $ID;
    public int $TYPE = 3;
    public string $NAME;
    public string $COMPANY_NAME;
    public string $SALUTATION;
    public string $FIRST_NAME;
    public string $MIDDLE_NAME;
    public string $LAST_NAME;
    public string $PRINT_NAME_AS;
    public string $POSTAL_ADDRESS;
    public string $CONTACT_PERSON;
    public string $TELEPHONE_NO;
    public string $FAX_NO;
    public string $MOBILE_NO;
    public string $ALT_TELEPHONE_NO;
    public string $ALT_CONTACT_PERSON;
    public string $EMAIL;
    public string $ACCOUNT_NO;
    public bool $INACTIVE;
    public int $GROUP_ID;
    public int $PAYMENT_TERMS_ID;
    public float $CREDIT_LIMIT;
    public int $PREF_PAYMENT_METHOD_ID;
    public string $CREDIT_CARD_NO;
    public string $CREDIT_CARD_EXPIRY_DATE;
    public int $SALES_REP_ID;
    public int $PRICE_LEVEL_ID;
    public string $TAXPAYER_ID;
    public int $TAX_ID;
    public int $EW_TAX_ID;
    public string $SSS_NO;
    public int $GENDER;
    public string $DATE_OF_BIRTH;
    public string $NICKNAME;
    public string $HIRE_DATE;

    public $taxList = [];
    public $contactGroup = [];
    public $paymentTermList = [];
    public $salesMan = [];
    public $paymentMethod = [];
    public $priceLevels = [];
    public $age = null;
    public function updateddateofbirth()
    {
        $this->age = $this->contactServices->calculateUserAge($this->DATE_OF_BIRTH);
    }

    public string $selectTab = 'gen';
    public function SelectTab($tab)
    {
        $this->selectTab = $tab;
    }

    private $contactServices;
    public function boot(ContactServices $contactServices)
    {
        $this->contactServices = $contactServices;
    }

    public function mount($id = null)
    {
        $this->taxList = Tax::query()->select('ID', 'NAME')->where('TAX_TYPE', 3)->orderBy('ID', 'desc')->get();
        $this->salesMan = Contacts::query()->select('ID', 'NAME')->where('INACTIVE', '0')->where('TYPE', '2')->get();
        $this->contactGroup = ContactGroup::query()->where('TYPE', $this->TYPE)->get();
        $this->paymentTermList = PaymentTerms::query()->select('ID', 'DESCRIPTION')->where('INACTIVE', '0')->get();
        $this->paymentMethod = PaymentMethods::query()->select("ID", 'DESCRIPTION')->get();
        $this->priceLevels = PriceLevels::query()->select('ID', 'DESCRIPTION')->where('INACTIVE', '0')->get();

        if (is_numeric($id)) {

            $contact = Contacts::where('ID', $id)->where('TYPE', $this->TYPE)->first();

            if ($contact) {
                $this->ID = $contact->ID;
                $this->NAME = $contact->NAME;
                $this->COMPANY_NAME = $contact->COMPANY_NAME ? $contact->COMPANY_NAME : '';
                $this->SALUTATION = $contact->SALUTATION ? $contact->SALUTATION : '';
                $this->FIRST_NAME = $contact->FIRST_NAME ? $contact->FIRST_NAME : '';
                $this->MIDDLE_NAME = $contact->MIDDLE_NAME ? $contact->MIDDLE_NAME : '';
                $this->LAST_NAME = $contact->LAST_NAME ? $contact->LAST_NAME : '';
                $this->PRINT_NAME_AS = $contact->PRINT_NAME_AS ? $contact->PRINT_NAME_AS : '';
                $this->POSTAL_ADDRESS = $contact->POSTAL_ADDRESS ? $contact->POSTAL_ADDRESS : '';
                $this->CONTACT_PERSON = $contact->CONTACT_PERSON ? $contact->CONTACT_PERSON : '';
                $this->TELEPHONE_NO = $contact->TELEPHONE_NO ? $contact->TELEPHONE_NO : '';
                $this->FAX_NO = $contact->FAX_NO ? $contact->FAX_NO : '';
                $this->MOBILE_NO = $contact->MOBILE_NO ? $contact->MOBILE_NO : '';
                $this->ALT_TELEPHONE_NO = $contact->ALT_TELEPHONE_NO ? $contact->ALT_TELEPHONE_NO : '';
                $this->ALT_CONTACT_PERSON = $contact->ALT_CONTACT_PERSON ? $contact->ALT_CONTACT_PERSON : '';
                $this->EMAIL = $contact->EMAIL ? $contact->EMAIL : '';
                $this->ACCOUNT_NO = $contact->ACCOUNT_NO ? $contact->ACCOUNT_NO : '';
                $this->INACTIVE = $contact->INACTIVE;
                $this->GROUP_ID = $contact->GROUP_ID ? $contact->GROUP_ID : 0;
                $this->PAYMENT_TERMS_ID = $contact->PAYMENT_TERMS_ID ? $contact->PAYMENT_TERMS_ID : 0;
                $this->CREDIT_LIMIT = $contact->CREDIT_LIMIT ? $contact->CREDIT_LIMIT : 0;
                $this->PREF_PAYMENT_METHOD_ID = $contact->PREF_PAYMENT_METHOD_ID ? $contact->PREF_PAYMENT_METHOD_ID : 0;
                $this->CREDIT_CARD_NO = $contact->CREDIT_CARD_NO ? $contact->CREDIT_CARD_NO : '';
                $this->CREDIT_CARD_EXPIRY_DATE = $contact->CREDIT_CARD_EXPIRY_DATE ? $contact->CREDIT_CARD_EXPIRY_DATE : '';
                $this->SALES_REP_ID = $contact->SALES_REP_ID ? $contact->SALES_REP_ID : 0;
                $this->PRICE_LEVEL_ID = $contact->PRICE_LEVEL_ID ? $contact->PRICE_LEVEL_ID : 0;
                $this->TAXPAYER_ID = $contact->TAXPAYER_ID ? $contact->TAXPAYER_ID : '';
                $this->TAX_ID = $contact->TAX_ID ? $contact->TAX_ID : 0;
                $this->EW_TAX_ID = $contact->EW_TAX_ID ? $contact->EW_TAX_ID : 0;
                $this->SSS_NO = $contact->SSS_NO ? $contact->SSS_NO : 0;
                $this->GENDER = $contact->GENDER ? $contact->GENDER : 0;
                $this->DATE_OF_BIRTH = $contact->DATE_OF_BIRTH ? $contact->DATE_OF_BIRTH : '';
                $this->NICKNAME = $contact->NICKNAME ? $contact->NICKNAME : '';
                $this->HIRE_DATE = $contact->HIRE_DATE ? $contact->HIRE_DATE : '';
                $this->updateddateofbirth();
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenancecontactpatients')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->NAME = '';
        $this->COMPANY_NAME = '';
        $this->SALUTATION = '';
        $this->FIRST_NAME = '';
        $this->MIDDLE_NAME = '';
        $this->LAST_NAME = '';
        $this->PRINT_NAME_AS = '';
        $this->POSTAL_ADDRESS = '';
        $this->CONTACT_PERSON = '';
        $this->TELEPHONE_NO = '';
        $this->FAX_NO = '';
        $this->MOBILE_NO = '';
        $this->ALT_TELEPHONE_NO = '';
        $this->ALT_CONTACT_PERSON = '';
        $this->EMAIL = '';
        $this->ACCOUNT_NO = '';
        $this->INACTIVE = false;
        $this->GROUP_ID = 0;
        $this->PAYMENT_TERMS_ID = 0;
        $this->CREDIT_LIMIT = 0;
        $this->PREF_PAYMENT_METHOD_ID = 0;
        $this->CREDIT_CARD_NO = '';
        $this->CREDIT_CARD_EXPIRY_DATE = '';
        $this->SALES_REP_ID = 0;
        $this->PRICE_LEVEL_ID = 0;
        $this->TAXPAYER_ID = '';
        $this->TAX_ID = 0;
        $this->EW_TAX_ID = 0;
        $this->SSS_NO = 0;
        $this->GENDER = 0;
        $this->DATE_OF_BIRTH = '';
        $this->NICKNAME = '';
        $this->HIRE_DATE = '';
        $this->age = null;
    }

    public function FullName()
    {
        if ($this->MIDDLE_NAME) {
            $firstLetter = substr($this->MIDDLE_NAME, 0, 1); // Get the first character of the middle name
            $fullname = $this->FIRST_NAME . ' ' . $firstLetter . '. ' . $this->LAST_NAME;
        } else {
            // Handle case where middle name is empty
            $fullname = $this->FIRST_NAME . ' ' . $this->MIDDLE_NAME . ' ' . $this->LAST_NAME;
        }

        $this->NAME = strtoupper($fullname);
        $this->PRINT_NAME_AS = $this->NAME;
    }
    public function updatedlastname()
    {
        $this->FullName();
    }
    public function updatedfirstname()
    {
        $this->FullName();
    }
    public function updatedmiddlename()
    {
        $this->FullName();
    }
    public function save()
    {
        if ($this->TAXPAYER_ID) {
            $this->validate(
                [
                    'NAME' => 'required|max:100|unique:contact,name,' . $this->ID,
                    'FIRST_NAME' => 'required',
                    'LAST_NAME' => 'required',
                    'TAXPAYER_ID' => 'required|max:100|unique:contact,taxpayer_id,' . $this->ID,
                    'DATE_OF_BIRTH' => 'required',
                ],
                [],
                [
                    'NAME' => 'Name',
                    'FIRST_NAME' => 'Firstname',
                    'LAST_NAME' => 'Lastname',
                    'TAXPAYER_ID' => 'Philhealth No.',
                    'DATE_OF_BIRTH' => 'Date of Birth',
                ]
            );
        } else {

            $this->validate(
                [
                    'NAME' => 'required|max:100|unique:contact,name,' . $this->ID,
                    'FIRST_NAME' => 'required',
                    'LAST_NAME' => 'required',
                    'DATE_OF_BIRTH' => 'required',
                ],
                [],
                [
                    'NAME' => 'Name',
                    'FIRST_NAME' => 'Firstname',
                    'LAST_NAME' => 'Lastname',
                    'DATE_OF_BIRTH' => 'Date of Birth',

                ]
            );
        }


        try {
            if ($this->ID === 0) {
                $this->ID = $this->contactServices->Store(
                    $this->TYPE,
                    $this->NAME,
                    $this->COMPANY_NAME ?? null,
                    $this->SALUTATION ?? null,
                    $this->FIRST_NAME ?? null,
                    $this->MIDDLE_NAME ?? null,
                    $this->LAST_NAME ?? null,
                    $this->PRINT_NAME_AS ?? null,
                    $this->POSTAL_ADDRESS,
                    $this->CONTACT_PERSON,
                    $this->TELEPHONE_NO,
                    $this->FAX_NO,
                    $this->MOBILE_NO,
                    $this->ALT_TELEPHONE_NO ?? null,
                    $this->ALT_CONTACT_PERSON,
                    $this->EMAIL,
                    $this->ACCOUNT_NO,
                    $this->INACTIVE,
                    $this->GROUP_ID,
                    $this->PAYMENT_TERMS_ID,
                    $this->CREDIT_LIMIT,
                    $this->PREF_PAYMENT_METHOD_ID,
                    $this->CREDIT_CARD_NO,
                    $this->CREDIT_CARD_EXPIRY_DATE,
                    $this->SALES_REP_ID,
                    $this->PRICE_LEVEL_ID,
                    $this->TAXPAYER_ID,
                    $this->TAX_ID,
                    $this->EW_TAX_ID,
                    $this->SSS_NO,
                    $this->GENDER,
                    $this->DATE_OF_BIRTH,
                    $this->NICKNAME,
                    $this->HIRE_DATE
                );
                $this->mount($this->ID);
                session()->flash('message', 'Successfully created');
            } else {
                $this->contactServices->Update(
                    $this->ID,
                    $this->TYPE,
                    $this->NAME,
                    $this->COMPANY_NAME,
                    $this->SALUTATION,
                    $this->FIRST_NAME,
                    $this->MIDDLE_NAME,
                    $this->LAST_NAME,
                    $this->PRINT_NAME_AS,
                    $this->POSTAL_ADDRESS,
                    $this->CONTACT_PERSON,
                    $this->TELEPHONE_NO,
                    $this->FAX_NO,
                    $this->MOBILE_NO,
                    $this->ALT_TELEPHONE_NO,
                    $this->ALT_CONTACT_PERSON,
                    $this->EMAIL,
                    $this->ACCOUNT_NO,
                    $this->INACTIVE,
                    $this->GROUP_ID,
                    $this->PAYMENT_TERMS_ID,
                    $this->CREDIT_LIMIT,
                    $this->PREF_PAYMENT_METHOD_ID,
                    $this->CREDIT_CARD_NO,
                    $this->CREDIT_CARD_EXPIRY_DATE,
                    $this->SALES_REP_ID,
                    $this->PRICE_LEVEL_ID,
                    $this->TAXPAYER_ID,
                    $this->TAX_ID,
                    $this->EW_TAX_ID,
                    $this->SSS_NO,
                    $this->GENDER,
                    $this->DATE_OF_BIRTH,
                    $this->NICKNAME,
                    $this->HIRE_DATE
                );
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        return view('livewire.patient.patient-form');
    }
}
