<?php
namespace App\Livewire\PatientReport;

use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Annex C Print")]
class PhilhealthAnnexTwoPrint extends Component
{
    public int $YEAR;
    public $dataList = [];
    private $philHealthServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    public function boot(PhilHealthServices $philHealthServices, LocationServices $locationServices, UserServices $userServices, DateServices $dateServices)
    {
        $this->philHealthServices = $philHealthServices;
        $this->locationServices   = $locationServices;
        $this->userServices       = $userServices;
        $this->dateServices       = $dateServices;
    }

    public function mount(int $locationid, int $year, int $month)
    {
        $this->YEAR     = $year;
        $this->dataList = $this->philHealthServices->GenerateAnnex($year, $month, $locationid, 1);
        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }

    public function render()
    {
        return view('livewire.patient-report.philhealth-annex-two-print');
    }
}
