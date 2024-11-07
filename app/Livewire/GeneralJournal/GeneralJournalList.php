<?php

namespace App\Livewire\GeneralJournal;

use App\Services\GeneralJournalServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("General Journal")]
class GeneralJournalList extends Component
{
    use WithPagination;
    public int $perPage = 15;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $generalJournalServices;
    private $locationServices;
    private $userServices;
    public function boot(
        GeneralJournalServices $generalJournalServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->generalJournalServices = $generalJournalServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function updatedlocationid()
    {   
        try {
            $this->userServices->SwapLocation($this->locationid );
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
 
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $this->generalJournalServices->Delete($id);
            DB::commit();
            session()->flash('message', 'Successfully deleted.');

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        $dataList = $this->generalJournalServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.general-journal.general-journal-list', ['dataList' => $dataList]);
    }
}
