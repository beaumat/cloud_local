<?php

namespace App\Livewire\GeneralJournal;

use App\Services\ContactServices;
use App\Services\GeneralJournalServices;
use App\Services\LocationServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("General Journal Print")]
class GeneralJournalPrint extends Component
{

    public int $ID;
    public string $DATE;
    public string $CODE;
    public string $CONTACT_NAME;
    public int $LOCATION_ID;
    public string $LOCATION_NAME;
    public string $NOTES;
    private $generalJournalServices;
    private $contactServices;
    private $locationServices;

    public function boot(
        GeneralJournalServices $generalJournalServices,
        ContactServices $contactServices,
        LocationServices $locationServices
    ) {
        $this->generalJournalServices = $generalJournalServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;

    }

    public function mount($id = null)
    {
        if (is_numeric($id)) {
            $data = $this->generalJournalServices->Get($id);
            if ($data) {
            



            }
        }
    }

    public function render()
    {
        return view('livewire.general-journal.general-journal-print');
    }
}
