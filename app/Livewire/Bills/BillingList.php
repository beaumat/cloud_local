<?php

namespace App\Livewire\Bills;
use Illuminate\Support\Facades\Auth;
use App\Models\Locations;
use Livewire\Component;

class BillingList extends Component
{   
    public $billList = [];
    public $search = '';
    public int $locationid;
    public $locationList = [];

    public function mount()
    {
        $this->locationList = Locations::query()->select(['ID', 'NAME'])->where('INACTIVE', '0')->get();
        $this->locationid =  Auth::user()->location_id ?? 0;
    }
    public function render()
    {
        return view('livewire.bills.billing-list');
    }
}
