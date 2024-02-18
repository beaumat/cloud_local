<?php

namespace App\Livewire\Option;

use App\Services\SystemSettingServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Illuminate\Support\Str;

class OptionSettingsCompany extends Component
{

    #[Reactive]
    public $systemSetting = [];
   
    public string $CompanyName;

    public string $CompanyAddress;

    public string $CompanyEmailAddress;

    public string $CompanyFaxNo;

    public string $CompanyMobileNo;

    public string $CompanyPhoneNo;

    public string $CompanyTin;


    public function mount(SystemSettingServices $systemSettingServices)
    {

        $this->CompanyName = $this->returnArray('CompanyName');
        $this->CompanyAddress = $this->returnArray('CompanyAddress');
        $this->CompanyEmailAddress = $this->returnArray('CompanyEmailAddress');
        $this->CompanyFaxNo = $this->returnArray('CompanyFaxNo');
        $this->CompanyMobileNo = $this->returnArray('CompanyMobileNo');
        $this->CompanyPhoneNo = $this->returnArray('CompanyPhoneNo');
        $this->CompanyTin = $this->returnArray('CompanyTin');
    }

    public function returnArray($name): string
    {

        foreach ($this->systemSetting as $list) {

            if (Str::lower($list->NAME) == Str::lower($name)) {

                return $list->VALUE;
            }
        }

        dd("record not found : " . $name);

    }
    public function saveOn($name, $value, SystemSettingServices $systemSettingServices)
    {   
        $systemSettingServices->SetValue($name, $value);
        session()->flash('message', $name . ' HAS BEEN SAVE!');
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        return view('livewire.option.option-settings-company');
    }
}
