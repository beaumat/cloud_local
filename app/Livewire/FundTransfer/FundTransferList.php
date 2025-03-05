<?php

namespace App\Livewire\FundTransfer;

use App\Services\AccountJournalServices;
use App\Services\FundTransferServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Fund Transfer')]
class FundTransferList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $fundTransferServices;
    private $locationServices;
    private $userServices;
    private $accountJournalServices;
    public function boot(
        FundTransferServices $fundTransferServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->fundTransferServices = $fundTransferServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->accountJournalServices = $accountJournalServices;
    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function delete($id)
    {

        $data = $this->fundTransferServices->Get($id);

        if ($data) {


            try {
                DB::beginTransaction();

                $JOURNAL_NO = $this->accountJournalServices->getRecord(
                    $this->fundTransferServices->object_type_id,
                    $id
                );

                if ($JOURNAL_NO > 0) {
                    $data = $this->fundTransferServices->get($id);
                    if ($data) {
                        // FROM
                        $this->accountJournalServices->DeleteJournal(
                            $data->FROM_ACCOUNT_ID,
                            $data->FROM_LOCATION_ID,
                            $JOURNAL_NO,
                            $data->FROM_NAME_ID ?? 0,
                            $data->ID,
                            $this->fundTransferServices->object_type_id,
                            $data->DATE,
                            1
                        );
                        $this->accountJournalServices->DeleteJournal(
                            $data->INTER_LOCATION_ACCOUNT_ID,
                            $data->FROM_LOCATION_ID,
                            $JOURNAL_NO,
                            $data->FROM_NAME_ID ?? 0,
                            $data->ID,
                            $this->fundTransferServices->object_type_id,
                            $data->DATE,
                            0
                        );



                        // TO
                        $this->accountJournalServices->DeleteJournal(
                            $data->TO_ACCOUNT_ID,
                            $data->TO_LOCATION_ID,
                            $JOURNAL_NO,
                            $data->TO_NAME_ID ?? 0,
                            $data->ID,
                            $this->fundTransferServices->object_type_id,
                            $data->DATE,
                            0
                        );
                        // TO
                        $this->accountJournalServices->DeleteJournal(
                            $data->INTER_LOCATION_ACCOUNT_ID,
                            $data->TO_LOCATION_ID,
                            $JOURNAL_NO,
                            $data->TO_NAME_ID ?? 0,
                            $data->ID,
                            $this->fundTransferServices->object_type_id,
                            $data->DATE,
                            1
                        );

                    }
                }

                $this->fundTransferServices->Delete($id);
                DB::commit();
                session()->flash('message', 'Successfully deleted.');
            } catch (\Exception $e) {
                DB::rollBack();
                $errorMessage = 'Error occurred: ' . $e->getMessage();
                session()->flash('error', $errorMessage);
            }
            return;
        }
    }
    public function render()
    {
        $dataList = $this->fundTransferServices->Search($this->search, $this->locationid);

        return view('livewire.fund-transfer.fund-transfer-list', ['dataList' => $dataList]);
    }
}
