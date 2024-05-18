<?php

namespace App\Services;

use App\Models\Accounts;

class AccountServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function getBankAccount()
    {
        return Accounts::whereIn('TYPE', ['0', '6'])->get();

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
            return Accounts::query()->select(['ID', 'TAG as CODE'])->where('INACTIVE', '0')->get();
        }
        return Accounts::query()->select(['ID', 'NAME as DESCRIPTION'])->where('INACTIVE', '0')->get();
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

        Accounts::where('ID', $ID)->update([
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
                    ->orWhere('account.TAG', 'like', '%' . $search . '%');
            })
            ->orderBy('account.ID', 'desc')
            ->get();
    }
}
