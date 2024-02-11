<?php

namespace App\Livewire\Employees;

use App\Services\ContactServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Employees')]
class EmployeeList extends Component
{   
    public $contacts = [];
    public $search = '';
    public function updatedsearch(ContactServices $contactServices)
    {
        $this->contacts = $contactServices->Search($this->search,2);
    }
    public function delete($id, ContactServices $contactServices)
    {
        try {
            $contactServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->contacts = $contactServices->Search($this->search,2);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(ContactServices $contactServices)
    {
        $this->contacts = $contactServices->Search($this->search,2);
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
        return view('livewire.employees.employee-list');
    }
}
