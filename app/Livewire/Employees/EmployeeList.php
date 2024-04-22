<?php

namespace App\Livewire\Employees;

use App\Services\ContactServices;
use App\Services\LocationServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Employees')]
class EmployeeList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 15;
    public $locationList = [];
    public int $locationid = 0;
    private $contactServices;
    private $locationServices;
    public function boot(
        ContactServices $contactServices,
        LocationServices $locationServices
    ) {
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
       
    }
    public function delete($id)
    {
        try {
            $this->contactServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');

        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = 0;
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
        $dataList = $this->contactServices->Search($this->search, 2, 15, $this->locationid);
        return view('livewire.employees.employee-list', ['dataList' => $dataList]);
    }
}
