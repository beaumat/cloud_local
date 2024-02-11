<?php

namespace App\Livewire\PurchaseOrder;

use App\Models\Locations;
use App\Services\PurchaseOrderServices;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Purchase Order')]
class PurchaseOrderList extends Component
{
    public $poList = [];
    public $search = '';
    public int $locationid;
    public $locationList = [];

    public function updatedlocationid(PurchaseOrderServices $purchaseOrderServices)
    {
        $this->poList = $purchaseOrderServices->Search($this->search, $this->locationid);
    }
    public function updatedsearch(PurchaseOrderServices $purchaseOrderServices)
    {
        $this->poList = $purchaseOrderServices->Search($this->search, $this->locationid);
    }
    public function delete($id, PurchaseOrderServices $purchaseOrderServices)
    {
        try {
            $purchaseOrderServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->poList = $purchaseOrderServices->Search($this->search, $this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(PurchaseOrderServices $purchaseOrderServices)
    {

        $this->locationList = Locations::query()->select(['ID', 'NAME'])->where('INACTIVE', '0')->get();
        $this->locationid =  Auth::user()->location_id ?? 0;
        $this->poList = $purchaseOrderServices->Search($this->search, $this->locationid);
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
        return view('livewire.purchase-order.purchase-order-list');
    }
}
