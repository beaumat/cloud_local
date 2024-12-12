<?php

namespace App\Services;

use App\Models\Depreciation;
use App\Models\DepreciationItems;

class DepreciationServices
{
    private $object;
    private $systemSettingServices;
    private $dateServices;
    public function __construct(ObjectServices $objectServices, SystemSettingServices $systemSettingServices, DateServices $dateServices)
    {
        $this->object = $objectServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices = $dateServices;
    }

    public function Store(string $CODE, string $DATE, int $LOCATION_ID, int $DEPRECIATION_ACCOUNT_ID, string $NOTES, bool $IS_AUTO): int
    {
        $ID = $this->object->ObjectNextID('DEPRECIATION');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('DEPRECIATION');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Depreciation::create(
            [
                'ID'                        => $ID,
                'RECORDED_ON'               => $this->dateServices->Now(),
                'CODE'                      => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
                'DATE'                      => $DATE,
                'LOCATION_ID'               => $LOCATION_ID,
                'DEPRECIATION_ACCOUNT_ID'   => $DEPRECIATION_ACCOUNT_ID > 0 ? $DEPRECIATION_ACCOUNT_ID : null,
                'NOTES'                     => $NOTES,
                'IS_AUTO'                   => $IS_AUTO,
                'AMOUNT'                    => 0,
                'STATUS'                    => 0,
                'STATUS_DATE'               => $this->dateServices->NowDate()
            ]
        );

        return $ID;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        Depreciation::where('ID', $ID)
            ->update([
                'STATUS'        => $STATUS,
                'STATUS_DATE'   => $this->dateServices->NowDate()
            ]);
    }

    public function Recomputed(int $ID)
    {
        $AMOUNT = (float)  DepreciationItems::where('DEPRECIATION_ID', '=', $ID)->sum('AMOUNT');

        Depreciation::where('ID', '=', $ID)->update(['AMOUNT' => $AMOUNT]);
    }
    public function Update(int $ID, string $CODE, int $DEPRECIATION_ACCOUNT_ID, string $NOTES)
    {

        Depreciation::where('ID', '=', $ID)
            ->update([
                'CODE'                      => $CODE,
                'DEPRECIATION_ACCOUNT_ID'   => $DEPRECIATION_ACCOUNT_ID > 0 ? $DEPRECIATION_ACCOUNT_ID : null,
                'NOTES'                     => $NOTES,
            ]);
    }
    public function Delete(int $ID)
    {
        Depreciation::where('ID', '=', $ID)->delete();
    }
    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        $result =  Depreciation::query()
            ->select([
                'depreciation.ID',
                'depreciation.CODE',
                'depreciation.DATE',
                'l.NAME as LOCATION_NAME',
                'a.NAME as ACCOUNT_NAME',
                's.DESCRIPTION as STATUS',
                'depreciation.AMOUNT',
            ])
            ->join('location as l', 'l.ID', '=', 'depreciation.LOCATION_ID')
            ->join('account as a', 'a.ID', '=', 'depreciation.DEPRECIATION_ACCOUNT_ID')
            ->join('document_status_map as s', 's.ID', '=', 'depreciation.STATUS')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->orWhere('depreciation.CODE', 'like', "%$search%");
                });
            })
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('depreciation.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->orderBy('depreciation.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function ItemStore(int $DEPRECIATION_ID, int $FIXED_ASSET_ITEM_ID, float $AMOUNT, int $ACCOUNT_ID)
    {
        $ID = $this->object->ObjectNextID('DEPRECIATION_ITEMS');
        
        DepreciationItems::create([
            'ID'                        => $ID,
            'DEPRECIATION_ID'           => $DEPRECIATION_ID,
            'FIXED_ASSET_ITEM_ID'       => $FIXED_ASSET_ITEM_ID,
            'AMOUNT'                    => $AMOUNT,
            'ACCOUNT_ID'                => $ACCOUNT_ID
        ]);
    }
    public function ItemUpdate(int $ID, float $AMOUNT, int $ACCOUNT_ID)
    {
        DepreciationItems::where('ID', '=', $ID)
            ->update([
                'ID'                        => $ID,
                'AMOUNT'                    => $AMOUNT,
                'ACCOUNT_ID'                => $ACCOUNT_ID
            ]);
    }
    public function ItemDelete(int $ID)
    {
        DepreciationItems::where('ID', '=', $ID)->delete();
    }
    public function ItemList(int $DEPRECIATION_ID)
    {
        $result = DepreciationItems::query()
            ->select([
                'depreciation_items.ID',
                'depreciation_items.DEPRECIATION_ID',
                'depreciation_items.FIXED_ASSET_ITEM_ID',
                'depreciation_items.ACCOUNT_ID',
                'depreciation_items.AMOUNT',
                'i.DESCRIPTION as ITEM_NAME',
                'f.ID as ASSET_ITEM_ID',
                'a.NAME as ACCOUNT_NAME'
            ])
            ->join('fixed_asset_item as f', 'f.ID', '= depreciation_items.FIXED_ASSET_ITEM_ID')
            ->join('item as i', 'i.ID', '=', 'f.ITEM_ID')
            ->join('account as a', 'a.ID', 'depreciation_items.ACCOUNT_ID')
            ->where('depreciation_items.DEPRECIATION_ID', '=', $DEPRECIATION_ID)
            ->get();


        return $result;
    }
}
