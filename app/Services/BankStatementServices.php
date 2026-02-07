<?php
namespace App\Services;

use App\Enums\TableName;
use App\Models\BankStatement;
use App\Models\BankStatementDetails;
use Illuminate\Support\Collection;

class BankStatementServices
{
    private $dateServices;
    private $objectServices;
    public function __construct(DateServices $dateServices, ObjectServices $objectServices)
    {
        $this->dateServices   = $dateServices;
        $this->objectServices = $objectServices;
    }
    public function get(int $id)
    {
        $data = BankStatement::where("ID", $id)->first();
        return $data;
    }
    public function store(string $DATE, string $DESCRIPTION, int $BANK_ACCOUNT_ID, int $FILE_TYPE, string $NOTES): int
    {

        $ID = (int) $this->objectServices->ObjectNextID(TableName::BANK_STATEMENT->value);

        BankStatement::create([
            'ID'              => $ID,
            'DATE'            => $DATE,
            'RECORDED_ON'     => $this->dateServices->Now(),
            'DESCRIPTION'     => $DESCRIPTION,
            'BANK_ACCOUNT_ID' => $BANK_ACCOUNT_ID,
            'FILE_TYPE'       => $FILE_TYPE,
            'NOTES'           => $NOTES,
        ]);

        return $ID;
    }
    public function uploadFile(int $ID, $FILE_NAME, $FILE_PATH)
    {
        BankStatement::where('ID', '=', $ID)
            ->update([
                'FILE_NAME' => $FILE_NAME,
                'FILE_PATH' => $FILE_PATH,
            ]);
    }
    public function update(int $ID, string $DATE, string $DESCRIPTION, int $BANK_ACCOUNT_ID, int $FILE_TYPE, string $NOTES)
    {
        BankStatement::where('ID', '=', $ID)
            ->update([
                'DATE'            => $DATE,
                'DESCRIPTION'     => $DESCRIPTION,
                'BANK_ACCOUNT_ID' => $BANK_ACCOUNT_ID,
                'FILE_TYPE'       => $FILE_TYPE,
                'NOTES'           => $NOTES,
            ]);
    }
    public function delete(int $ID)
    {
        BankStatementDetails::where('BANK_STATEMENT_ID', '=', $ID)->delete();
        BankStatement::where('ID', '=', $ID)->delete();
    }

    public function Search($search)
    {
        $result = BankStatement::query()
            ->select([
                'bank_statement.ID',
                'bank_statement.DATE',
                'bank_statement.RECORDED_ON',
                'bank_statement.DESCRIPTION',
                'bank_statement.NOTES',
                'b.NAME as BANK_NAME',
                'bank_statement.RECON_STATUS',
                'bank_statement.RECON_DATE',
                't.DESCRIPTION as FILE_TYPE',
            ])
            ->join('account as b', 'b.ID', '=', 'bank_statement.BANK_ACCOUNT_ID')
            ->join('file_type_map as t', 't.ID', '=', 'bank_statement.FILE_TYPE')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('bank_statement.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('bank_statement.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('b.NAME', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('bank_statement.ID', 'desc')
            ->paginate(30);

        return $result;
    }

    public function storeDetails(int $BANK_STATEMENT_ID, string $DATE_TRANSACTION, string $REFERENCE, string $DESCRIPTION, string $CHECK_NUMBER, float $DEBIT, float $CREDIT, float $BALANCE)
    {

        $ID = (int) $this->objectServices->ObjectNextID(TableName::BANK_STATEMENT_DETAILS->value);

        BankStatementDetails::create([
            'ID'                => $ID,
            'BANK_STATEMENT_ID' => $BANK_STATEMENT_ID,
            'DATE_TRANSACTION'  => $this->dateServices->DateFormat($DATE_TRANSACTION),
            'REFERENCE'         => $REFERENCE,
            'DESCRIPTION'       => $DESCRIPTION,
            'CHECK_NUMBER'      => $CHECK_NUMBER,
            'DEBIT'             => $DEBIT,
            'CREDIT'            => $CREDIT,
            'BALANCE'           => $BALANCE,
        ]);
    }
    public function updateDetails(int $ID, int $BANK_STATEMENT_ID, string $DATE_TRANSACTION, string $REFERENCE, string $DESCRIPTION, string $CHECK_NUMBER, float $DEBIT, float $CREDIT, float $BALANCE)
    {
        BankStatementDetails::where('ID', '=', $ID)
            ->update([
                'BANK_STATEMENT_ID' => $BANK_STATEMENT_ID,
                'DATE_TRANSACTION'  => $DATE_TRANSACTION,
                'REFERENCE'         => $REFERENCE,
                'DESCRIPTION'       => $DESCRIPTION,
                'CHECK_NUMBER'      => $CHECK_NUMBER,
                'DEBIT'             => $DEBIT,
                'CREDIT'            => $CREDIT,
                'BALANCE'           => $BALANCE,
            ]);
    }
    public function deleteDetails(int $ID)
    {
        BankStatementDetails::where('ID', '=', $ID)->delete();
    }
    public function listDetails(int $BANK_STATEMENT_ID): array | Collection
    {

        $result = BankStatementDetails::query()
            ->select([
                'ID',
                'BANK_STATEMENT_ID',
                'DATE_TRANSACTION',
                'REFERENCE',
                'DESCRIPTION',
                'CHECK_NUMBER',
                'DEBIT',
                'CREDIT',
                'BALANCE',
                'OBJECT_TYPE',
                'OBJECT_ID',
                'RECON_LOG',
            ])
            ->where('BANK_STATEMENT_ID', '=', $BANK_STATEMENT_ID)
            ->orderBy('ID')
            ->get();

        return $result;
    }

}
