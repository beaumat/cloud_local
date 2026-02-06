<?php
namespace App\Livewire\FundTransfer;

use App\Services\FundTransferReverseServices;
use App\Services\FundTransferServices;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReverseForm extends Component
{
    private $fundTransferServices;
    private $fundTransferReverseServices;

    public $FUND_TRANSFER_ID;

    public string $DATE  = '';
    public string $NOTES = '';
    public string $CODE  = '';
    public int $LOCATION_ID;

    public function boot(FundTransferServices $fundTransferServices, FundTransferReverseServices $fundTransferReverseServices)
    {
        $this->fundTransferServices        = $fundTransferServices;
        $this->fundTransferReverseServices = $fundTransferReverseServices;

    }
    public function mount(int $id)
    {
        $this->FUND_TRANSFER_ID = $id;
    }
    public function ReverseSave()
    {
        $exists = $this->fundTransferServices->Exists($this->FUND_TRANSFER_ID);

        if ($exists == false) {
            // not found!
            return;
        }

        DB::beginTransaction();
        try {

            $ID = (int) $this->fundTransferReverseServices->Store($this->DATE, $this->CODE, $this->NOTES, $this->FUND_TRANSFER_ID, $this->LOCATION_ID);
            if ($ID > 0) {
                // MAKE JOURNAL

            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
        }

    }

    public function render()
    {
        return view('livewire.fund-transfer.reverse-form');
    }
}
