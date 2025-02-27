<?php

namespace App\Services;

use App\Models\Accounts;
use App\Models\AccountType;

class AccountServices
{
    public int $ACCOUNTS_RECEIVABLE_ID = 4;
    public int $UNDEPOSITED_ACCOUNT_ID = 5;
    public int $EXPENSE_ACCOUNT_ID = 284;
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function GetTypeList()
    {
        $result = AccountType::get();

        return $result;
    }
    public function getBankAccount()
    {
        return Accounts::whereIn('TYPE', ['0', '6'])
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();
    }

    public function getBankAccountDeposit()
    {
        return Accounts::whereIn('TYPE', ['0', '6'])
            ->where('INACTIVE', '=', '0')
            ->orWhere('ID', '=', $this->UNDEPOSITED_ACCOUNT_ID)
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function getUndepositedList()
    {
        return Accounts::whereIn('TYPE', ['6'])
            ->where('INACTIVE', '=', '0')
            ->orWhere('ID', '=', $this->UNDEPOSITED_ACCOUNT_ID)
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function getExpenses()
    {
        return Accounts::whereIn('TYPE', ['12', '14'])
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function getPayable()
    {
        return Accounts::whereIn('TYPE', ['5', '6', '7', '8'])
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function getReceivable()
    {
        $result = Accounts::whereIn('TYPE', ['0', '1', '2', '3', '4'])
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();


        return $result;
    }
    public function getIncome()
    {
        return Accounts::whereIn('TYPE', ['10', '13'])
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function getPay()
    {
        return Accounts::whereIn('TYPE', ['0', '1', '2'])
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();
    }
    public function get(int $ID)
    {
        return Accounts::where('ID', $ID)->first();
    }
    public function getByName(string $NAME): int
    {
        $data = Accounts::where('NAME', $NAME)->first();
        if ($data) {
            return $data->ID;
        }

        return 0;
    }
    public function getAccount(bool $isCode)
    {

        if ($isCode) {

            $result = Accounts::query()
                ->select(['ID', 'TAG as CODE'])
                ->where('INACTIVE', '=', '0')
                ->orderBy('TAG', 'asc')
                ->get();

            return $result;
        }

        $result = Accounts::query()
            ->select([
                'account.ID',
                'account.NAME as DESCRIPTION',
                't.DESCRIPTION as TYPE'
            ])
            ->join('account_type_map as t', 't.ID', '=', 'account.TYPE')
            ->where('INACTIVE', '=', '0')
            ->orderBy('NAME', 'asc')
            ->get();

        return $result;
    }

    public function Store(string $NAME, int $GROUP_ACCOUNT_ID, int $TYPE, string $BANK_ACCOUNT_NO, bool $INACTIVE, string $TAG, int $LINE_NO): int
    {
        $ID = $this->object->ObjectNextID('ACCOUNT');

        Accounts::create([
            'ID' => $ID,
            'NAME' => $NAME,
            'GROUP_ACCOUNT_ID' => $GROUP_ACCOUNT_ID > 0 ? $GROUP_ACCOUNT_ID : null,
            'TYPE' => $TYPE,
            'BANK_ACCOUNT_NO' => $BANK_ACCOUNT_NO,
            'INACTIVE' => $INACTIVE,
            'TAG' => $TAG,
            'LINE_NO' => $LINE_NO

        ]);

        return $ID;
    }

    public function Update(int $ID, string $NAME, int $GROUP_ACCOUNT_ID, int $TYPE, string $BANK_ACCOUNT_NO, bool $INACTIVE, string $TAG, int $LINE_NO): void
    {

        Accounts::where('ID', '=', $ID)
            ->update([
                'NAME' => $NAME,
                'GROUP_ACCOUNT_ID' => $GROUP_ACCOUNT_ID > 0 ? $GROUP_ACCOUNT_ID : null,
                'TYPE' => $TYPE,
                'BANK_ACCOUNT_NO' => $BANK_ACCOUNT_NO,
                'INACTIVE' => $INACTIVE,
                'TAG' => $TAG,
                'LINE_NO' => $LINE_NO
            ]);
    }

    public function Delete(int $ID): void
    {
        Accounts::where('ID', $ID)->delete();
    }
    public function Inactive(int $ID, int $stats)
    {
        Accounts::where('ID', $ID)->update(['INACTIVE' => $stats]);
    }
    public function Search($search)
    {
        return Accounts::query()
            ->select(
                [
                    'account.ID',
                    'account.NAME',
                    'account.GROUP_ACCOUNT_ID',
                    'account.TYPE',
                    'account.BANK_ACCOUNT_NO',
                    'account.INACTIVE',
                    'account.TAG',
                    'account.LINE_NO',
                    'account_type_map.DESCRIPTION as ACCOUNT_TYPE',
                    'g.NAME as GROUP_ACCOUNT'
                ]
            )
            ->join('account_type_map', 'account_type_map.ID', '=', 'account.TYPE')
            ->leftJoin('account as g', 'g.ID', '=', 'account.GROUP_ACCOUNT_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('account.NAME', 'like', '%' . $search . '%')
                    ->orwhere('account_type_map.DESCRIPTION', 'like', '%' . $search . '%')
                    ->orWhere('account.TAG', 'like', '%' . $search . '%');
            })
            ->orderBy('account.TYPE', 'asc')
            ->get();
    }
    public function IncomeStatementMonthly()
    {
        $result = Accounts::query()
            ->select([
                'account.ID',
                'account.NAME',
                'account.TYPE'
            ])
            ->join('account_type_map', 'account_type_map.ID', '=', 'account.TYPE')
            ->where('account.INACTIVE', '=', 0)
            ->whereIn('account.TYPE', [10, 11, 12, 13, 14])
            ->orderByRaw("FIELD(account.TYPE, '10', '11', '13','12','14')")
            ->get();

        return $result;
    }
    public function BalanceSheetMonthly()
    {
        $result = Accounts::query()
            ->select([
                'account.ID',
                'account.NAME',
                'account.TYPE'
            ])
            ->join('account_type_map', 'account_type_map.ID', '=', 'account.TYPE')
            ->where('account.INACTIVE', '=', 0)
            ->whereIn('account.TYPE', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9])
            ->orderBy("account.TYPE", 'asc')
            ->get();

        return $result;
    }
}
