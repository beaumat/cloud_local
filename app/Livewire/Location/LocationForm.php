<?php

namespace App\Livewire\Location;

use App\Models\LocationGroup;
use App\Models\Locations;
use App\Models\PriceLevels;
use App\Services\ContactServices;
use App\Services\LocationServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Location - Form')]
class LocationForm extends Component
{
    public int $ID;
    public string $NAME;
    public bool $INACTIVE;
    public int $PRICE_LEVEL_ID;
    public int $GROUP_ID;
    public $priceLevels = [];
    public $locationGroups = [];

    public int $HCI_MANAGER_ID;
    public int $PHIC_INCHARGE_ID;
    public string $NAME_OF_BUSINESS;
    public string $ACCREDITATION_NO;
    public string $BLDG_NAME_LOT_BLOCK;
    public string $STREET_SUB_VALL;
    public string $BRGY_CITY_MUNI;
    public string $PROVINCE;
    public string $ZIP_CODE;
    public string $REPORT_HEADER_1;
    public string $REPORT_HEADER_2;
    public string $REPORT_HEADER_3;

    public $managerList = [];
    public $inchargeList = [];
    public $physicianList = [];

    private $locationServices;
    private $contactServices;
    public function boot(LocationServices $locationServices, ContactServices $contactServices)
    {
        $this->locationServices = $locationServices;
        $this->contactServices = $contactServices;
    }
    public function loadDropDown()
    {
        $this->priceLevels = PriceLevels::query()->select(['ID', 'DESCRIPTION'])->where('INACTIVE', '0')->where('TYPE', '0')->get();
        $this->locationGroups = LocationGroup::query()->select(['ID', 'NAME'])->where('INACTIVE', '0')->get();
        $contactList = $this->contactServices->getList(2);
        $this->managerList = $contactList;
        $this->inchargeList = $contactList;
        $this->physicianList = $contactList;
    }
    public function mount($id = null)
    {

        $this->loadDropDown();
        if (is_numeric($id)) {
            $location = Locations::where('ID', $id)->first();

            if ($location) {
                $this->ID = $location->ID;
                $this->NAME = $location->NAME;
                $this->INACTIVE = $location->INACTIVE;
                $this->PRICE_LEVEL_ID = $location->PRICE_LEVEL_ID ? $location->PRICE_LEVEL_ID : 0;
                $this->GROUP_ID = $location->GROUP_ID ? $location->GROUP_ID : 0;
                $this->HCI_MANAGER_ID = $location->HCI_MANAGER_ID ?? 0;
                $this->PHIC_INCHARGE_ID = $location->PHIC_INCHARGE_ID ?? 0;
                $this->NAME_OF_BUSINESS = $location->NAME_OF_BUSINESS ?? '';
                $this->ACCREDITATION_NO = $location->ACCREDITATION_NO ?? '';
                $this->BLDG_NAME_LOT_BLOCK = $location->BLDG_NAME_LOT_BLOCK ?? '';
                $this->STREET_SUB_VALL = $location->STREET_SUB_VALL ?? '';
                $this->BRGY_CITY_MUNI = $location->BRGY_CITY_MUNI ?? '';
                $this->PROVINCE = $location->PROVINCE ?? '';
                $this->ZIP_CODE = $location->ZIP_CODE ?? '';
                $this->REPORT_HEADER_1 = $location->REPORT_HEADER_1 ?? '';
                $this->REPORT_HEADER_2 = $location->REPORT_HEADER_2 ?? '';
                $this->REPORT_HEADER_3 = $location->REPORT_HEADER_3 ?? '';
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenancesettingslocation')->with('error', $errorMessage);
        }
        $this->ID = 0;
        $this->NAME = '';
        $this->PRICE_LEVEL_ID = 0;
        $this->GROUP_ID = 0;
        $this->INACTIVE = false;
        $this->HCI_MANAGER_ID = 0;
        $this->PHIC_INCHARGE_ID = 0;
        $this->NAME_OF_BUSINESS = '';
        $this->ACCREDITATION_NO = '';
        $this->BLDG_NAME_LOT_BLOCK = '';
        $this->STREET_SUB_VALL = '';
        $this->BRGY_CITY_MUNI = '';
        $this->PROVINCE = '';
        $this->ZIP_CODE = '';
        $this->REPORT_HEADER_1 =  '';
        $this->REPORT_HEADER_2 = '';
        $this->REPORT_HEADER_3 =  '';
    }


    public function save()
    {
        $this->validate([
            'NAME' => 'required|max:50|unique:location,name,' . $this->ID
        ], [], [
            'NAME' => 'Name'
        ]);

        try {
            if ($this->ID === 0) {
                $this->ID = $this->locationServices->Store(
                    $this->NAME,
                    $this->INACTIVE,
                    $this->PRICE_LEVEL_ID,
                    $this->GROUP_ID,
                    $this->HCI_MANAGER_ID,
                    $this->PHIC_INCHARGE_ID,
                    $this->NAME_OF_BUSINESS,
                    $this->ACCREDITATION_NO,
                    $this->BLDG_NAME_LOT_BLOCK,
                    $this->STREET_SUB_VALL,
                    $this->BRGY_CITY_MUNI,
                    $this->PROVINCE,
                    $this->ZIP_CODE,
                    $this->REPORT_HEADER_1,
                    $this->REPORT_HEADER_2,
                    $this->REPORT_HEADER_3
                );

                Redirect::route('maintenancesettingslocation_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                $this->locationServices->Update(
                    $this->ID,
                    $this->NAME,
                    $this->INACTIVE,
                    $this->PRICE_LEVEL_ID,
                    $this->GROUP_ID,
                    $this->HCI_MANAGER_ID,
                    $this->PHIC_INCHARGE_ID,
                    $this->NAME_OF_BUSINESS,
                    $this->ACCREDITATION_NO,
                    $this->BLDG_NAME_LOT_BLOCK,
                    $this->STREET_SUB_VALL,
                    $this->BRGY_CITY_MUNI,
                    $this->PROVINCE,
                    $this->ZIP_CODE,
                    $this->REPORT_HEADER_1,
                    $this->REPORT_HEADER_2,
                    $this->REPORT_HEADER_3
                );
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.location.location-form');
    }
}
