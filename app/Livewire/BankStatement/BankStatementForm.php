<?php
namespace App\Livewire\BankStatement;

use App\Services\AccountServices;
use App\Services\BankStatementServices;
use App\Services\DateServices;
use App\Services\FileTypeServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Bank Statement")]
class BankStatementForm extends Component
{

    private $bankStatementSerivce;
    private $accountServices;
    private $fileTypeServices;
    private $dateServices;
    public bool $Modify     = false;
    public $accountList     = [];
    public $fileTypeList    = [];
    public int $STATUS      = 0;
    public int $ID          = 0;
    public $BANK_ACCOUNT_ID = 0;

    public string $DATE;
    public string $DESCRIPTION = '';
    public int $FILE_TYPE      = 0;
    public string $NOTES       = '';
    public string $FILE_NAME   = '';
    public string $FILE_PATH   = '';

    public function boot(BankStatementServices $bankStatementServices, AccountServices $accountServices, FileTypeServices $fileTypeServices, DateServices $dateServices)
    {
        $this->bankStatementSerivce = $bankStatementServices;
        $this->accountServices      = $accountServices;
        $this->fileTypeServices     = $fileTypeServices;
        $this->dateServices         = $dateServices;
    }
    public function mount($id = null)
    {
        $this->fileTypeList = $this->fileTypeServices->getFileTypes();
        $this->accountList  = $this->accountServices->getBankAccount();

        if (is_numeric($id)) {

            $this->getInfo($id);

        } else {

            $this->ID     = 0;
            $this->Modify = true;
            $this->DATE   = $this->dateServices->NowDate();
        }
    }

    private function getInfo(int $ID)
    {
        $data = $this->bankStatementSerivce->get($ID);
        if ($data) {
            $this->ID              = $data->ID;
            $this->BANK_ACCOUNT_ID = $data->BANK_ACCOUNT_ID;
            $this->DATE            = $data->DATE;
            $this->DESCRIPTION     = $data->DESCRIPTION ?? '';
            $this->FILE_TYPE       = $data->FILE_TYPE;
            $this->NOTES           = $data->NOTES ?? '';
            $this->FILE_NAME       = $data->FILE_NAME ?? '';
            $this->FILE_PATH       = $data->FILE_PATH ?? '';

        } else {

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('bankingbank_statement')->with('error', $errorMessage);

        }
    }
    public function Save()
    {

        $this->validate(
            [
                'BANK_ACCOUNT_ID' => 'required|not_in:0|exists:account,id',
                'FILE_TYPE'       => 'required|not_in:0|exists:file_type_map,id',
                'DATE'            => 'required',
                'DESCRIPTION'     => 'required',

            ],
            [],
            [
                'BANK_ACCOUNT_ID' => 'Bank Account',
                'FILE_TYPE'       => 'File Type',
                'DATE'            => 'Date',
                'DESCRIPTION'     => 'Description',

            ]
        );
        if ($this->ID == 0) {

            try {
                DB::beginTransaction();
                $this->ID = $this->bankStatementSerivce->store($this->DATE,
                    $this->DESCRIPTION,
                    $this->BANK_ACCOUNT_ID,
                    $this->FILE_TYPE,
                    $this->NOTES);
                DB::commit();
                return Redirect::route('bankingbank_statement_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } catch (\Throwable $e) {

                DB::rollBack();
                session()->flash("error", $e->getMessage());
            }

        } else {

            try {
                DB::beginTransaction();
                $this->bankStatementSerivce->update($this->ID, $this->DATE, $this->DESCRIPTION, $this->BANK_ACCOUNT_ID, $this->FILE_TYPE, $this->NOTES);
                DB::commit();
                return Redirect::route('bankingbank_statement_edit', ['id' => $this->ID])->with('message', 'Successfully updated');
            } catch (\Throwable $e) {
                DB::rollBack();
                session()->flash("error", $e->getMessage());
            }

        }

    }

    public function getModify()
    {
        $this->Modify = true;
    }
    public function updateCancel()
    {
        return Redirect::route('bankingbank_statement_edit', ['id' => $this->ID]);
    }
    public function render()
    {
        return view('livewire.bank-statement.bank-statement-form');
    }
}
