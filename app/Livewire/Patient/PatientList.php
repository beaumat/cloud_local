<?php

namespace App\Livewire\Patient;

use App\Services\ContactServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Patients')]
class PatientList extends Component
{

    public $contacts = [];
    public $search = '';
    private $contactServices;
    public function boot(ContactServices $contactServices)
    {
        $this->contactServices = $contactServices;
    }
    public function updatedsearch()
    {
        $this->contacts = $this->contactServices->Search($this->search, 3);
    }
    public function delete($id)
    {
        try {
            $this->contactServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->contacts = $this->contactServices->Search($this->search, 3);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount()
    {
        $this->contacts = $this->contactServices->Search($this->search, 3);
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
        return view('livewire.patient.patient-list');
    }
}
