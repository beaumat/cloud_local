<?php

namespace App\Livewire\Vendor;

use App\Services\ContactServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Supplier ')]
class VendorList extends Component
{
    
    public $search = '';
    private $contactServices;
    public function boot(ContactServices $contactServices)
    {
            $this->contactServices = $contactServices;
    }
    public function updatedsearch()
    {
        $this->contacts = $this->contactServices->Search($this->search,0,15,0);
    }
    public function delete($id, ContactServices $contactServices)
    {
        try {
            $contactServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
      
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
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
        $contacts = $this->contactServices->Search($this->search,0,15,0);
        return view('livewire.vendor.vendor-list',['dataList' => $contacts]);
    }
}
