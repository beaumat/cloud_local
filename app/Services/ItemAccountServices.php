<?php

namespace App\Services;

use App\Models\Accounts;
use App\Models\ItemAccounts;
use Illuminate\Support\Facades\DB;

class ItemAccountServices
{


    public function Store(int $ITEM_ID, int $ACCOUNT_ID)
    {
        ItemAccounts::create([
            'ITEM_ID' => $ITEM_ID,
            'ACCOUNT_ID'  => $ACCOUNT_ID
        ]);
    }

    public function Delete(int $ITEM_ID, int $ACCOUNT_ID)
    {
        ItemAccounts::where('ITEM_ID', '=', $ITEM_ID)
            ->where('ACCOUNT_ID', '=', $ACCOUNT_ID)
            ->delete();
    }
    public function AccountList(int $ITEM_ID)
    {

        $result = ItemAccounts::select([
            'a.ID',
            'a.NAME'
        ])
            ->join('account as a', 'a.ID', '=', 'item_accounts.ACCOUNT_ID')
            ->where('a.INACTIVE', '=', false)
            ->where('item_accounts.ITEM_ID', '=', $ITEM_ID)
            ->get();

        return $result;
    }

    public function AccountAvailable(int $ITEM_ID)
    {

        $result = accounts::select(['account.ID', 'account.NAME'])
            ->where('account.INACTIVE', '=', false)
            ->whereNotExists(function ($query) use (&$ITEM_ID) {
                $query->select(DB::raw(1))
                    ->from('item_accounts as a')
                    ->whereRaw('a.ACCOUNT_ID = account.ID')
                    ->where('a.LOCATION_ID', $ITEM_ID);
            })
            ->get();

        return $result;
    }

    public function AccountSelected(int $ITEM_ID) {

        $result = accounts::select(['account.ID', 'account.NAME'])
        ->where('account.INACTIVE', '=', false)
        ->whereExists(function ($query) use (&$ITEM_ID) {
            $query->select(DB::raw(1))
                ->from('item_accounts as a')
                ->whereRaw('a.ACCOUNT_ID = account.ID')
                ->where('a.LOCATION_ID', $ITEM_ID);
        })
        ->get();
        
    return $result;

    }
}
