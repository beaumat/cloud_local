<?php

namespace App\Services;

use App\Models\PullOut;
use App\Models\PullOutItems;
use Illuminate\Support\Facades\DB;

class PullOutServices
{

    private $object;
    private $dateServices;
    private $systemSettingServices;
    public function __construct(ObjectServices $objectService, SystemSettingServices $systemSettingServices, DateServices $dateServices)
    {
        $this->object = $objectService;
        $this->dateServices = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
    }
    public function Get(int $ID)
    {
        return PullOut::where('ID', $ID)->first();
    }
    public function Store(string $CODE, string $DATE, int $LOCATION_ID, string $NOTES, int $PREPARED_BY_ID, int $ACCOUNT_ID): int
    {
        $ID = (int) $this->object->ObjectNextID('PULL_OUT');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('PULL_OUT');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        PullOut::create(
            [
                'ID' => $ID,
                'RECORDED_ON' => $this->dateServices->Now(),
                'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
                'DATE' => $DATE,
                'LOCATION_ID' => $LOCATION_ID,
                'AMOUNT' => 0,
                'NOTES' => $NOTES,
                'PREPARED_BY_ID' => $PREPARED_BY_ID > 0 ? $PREPARED_BY_ID : null,
                'STATUS' => 0,
                'STATUS_DATE' => $this->dateServices->NowDate(),
                'ACCOUNT_ID' => $ACCOUNT_ID
            ]
        );

        return $ID;
    }
    public function Update(int $ID, string $CODE, string $NOTES, int $PREPARED_BY_ID)
    {
        PullOut::where('ID', $ID)
            ->update([
                'CODE' => $CODE,
                'NOTES' => $NOTES,
                'PREPARED_BY_ID' => $PREPARED_BY_ID > 0 ? $PREPARED_BY_ID : null,
            ]);
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        PullOut::where('ID', $ID)
            ->update([
                'STATUS' => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate()
            ]);
    }
    public function Delete(int $ID)
    {
        PullOutItems::where('PULL_OUT_ID', $ID)->delete();
        PullOut::where('ID', $ID)->delete();
    }


    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        $result = PullOut::query()
            ->select([
                'pull_out.ID',
                'pull_out.CODE',
                'pull_out.DATE',
                'pull_out.AMOUNT',
                'pull_out.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'c.NAME as PREPARED_BY'
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'pull_out.PREPARED_BY_ID')
            ->join('document_status_map as s', 's.ID', '=', 'pull_out.STATUS')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'pull_out.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('pull_out.CODE', 'like', '%' . $search . '%')
                        ->orWhere('pull_out.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('pull_out.NOTES', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('pull_out.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }

    public function CountItems(int $PULL_OUT_ID): int
    {
        return (int) PullOutItems::where('PULL_OUT_ID', $PULL_OUT_ID)->count();
    }
    private function getLine($PULL_OUT_ID): int
    {
        return (int) PullOutItems::where('PULL_OUT_ID', $PULL_OUT_ID)->max('LINE_NO');
    }
    public function ItemStore(int $PULL_OUT_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, float $RATE, int $BATCH_ID, int $ASSET_ACCOUNT_ID)
    {

        $ID = $this->object->ObjectNextID('PULL_OUT');
        
        $LINE_NO = $this->getLine($PULL_OUT_ID) + 1;
        PullOutItems::create([
            'ID'            => $ID,
            'PULL_OUT_ID'   => $PULL_OUT_ID,
            'LINE_NO'       => $LINE_NO,
            'ITEM_ID'       => $ITEM_ID,
            'DESCRIPTION'   => null,
            'QUANTITY'      => $QUANTITY,
            'UNIT_ID'       => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'RATE'          => $RATE,
            'AMOUNT'        => $RATE * $QUANTITY,
            'BATCH_ID'      => $BATCH_ID > 0 ? $BATCH_ID : null,
            'ASSET_ACCOUNT_ID' => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : null
        ]);

        $this->UpdateTotal($PULL_OUT_ID);
    }
    public function GetItem(int $ID, int $PULL_OUT_ID)
    {
        return PullOutItems::where('ID', $ID)
            ->where('PULL_OUT_ID', $PULL_OUT_ID)
            ->first();
    }
    public function ItemUpdate(int $ID, int $PULL_OUT_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, float $RATE, int $BATCH_ID)
    {
        PullOutItems::where('ID', $ID)
            ->where('PULL_OUT_ID', $PULL_OUT_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->update([
                'ID'                    => $ID,
                'ITEM_ID'               => $ITEM_ID,
                'QUANTITY'              => $QUANTITY,
                'UNIT_ID'               => $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY'    => $UNIT_BASE_QUANTITY,
                'RATE'                  => $RATE,
                'AMOUNT'                => $RATE * $QUANTITY,
                'BATCH_ID'              => $BATCH_ID > 0 ? $BATCH_ID : null
            ]);

        $this->UpdateTotal($PULL_OUT_ID);
    }
    public function ItemDelete(int $ID, int $PULL_OUT_ID,)
    {
        PullOutItems::where('ID', $ID)
            ->where('PULL_OUT_ID', $PULL_OUT_ID)
            ->delete();

        $this->UpdateTotal($PULL_OUT_ID);
    }
    public function UpdateTotal(int $PULL_OUT_ID)
    {
        $result = $this->GetTotal($PULL_OUT_ID);
        
        PullOut::where('ID', $PULL_OUT_ID)
            ->update(
                [
                    'AMOUNT' => $result['AMOUNT']
                ]
            );
    }

    private function GetTotal(int $PULL_OUT_ID)
    {

        $result = PullOutItems::query()
            ->select([
                DB::raw(' ifnull(sum(AMOUNT),2) as AMOUNT'),
            ])
            ->where('PULL_OUT_ID', $PULL_OUT_ID)
            ->first();

        if ($result) {
            return [
                'AMOUNT' => $result->AMOUNT
            ];
        }

        return [
            'AMOUNT' => 0,
        ];
    }
    public function ItemView(int $PULL_OUT_ID)
    {
        $result = PullOutItems::query()
            ->select([
                'pull_out_items.ID',
                'pull_out_items.ITEM_ID',
                'pull_out_items.QUANTITY',
                'pull_out_items.UNIT_ID',
                'pull_out_items.RATE',
                'pull_out_items.AMOUNT',
                'item.CODE',
                'item.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL'
            ])
            ->leftJoin('item', 'item.ID', '=', 'pull_out_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'pull_out_items.UNIT_ID')
            ->where('pull_out_items.PULL_OUT_ID', $PULL_OUT_ID)
            ->orderBy('pull_out_items.LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function ItemInventory(int $PULL_OUT_ID)
    {
        $result = PullOutItems::query()
            ->select([
                'pull_out_items.ID',
                'pull_out_items.ITEM_ID',
                'pull_out_items.QUANTITY',
                'pull_out_items.UNIT_BASE_QUANTITY',
                'item.COST'
            ])
            ->join('item', 'item.ID', '=', 'pull_out_items.ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('pull_out_items.PULL_OUT_ID', $PULL_OUT_ID)
            ->get();

        return $result;
    }

    public function getPullOutJournal(int $PULL_OUT_ID)
    {
        $result = PullOut::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                DB::raw(" 0 as SUBSIDIARY_ID"),
                'AMOUNT',
                DB::raw(" 0 as ENTRY_TYPE"),
                DB::raw("'SOURCEACCOUNT' as EXTENDED_OPTIONS"),
                DB::raw("YEAR(DATE) as SEQUENCE_GROUP")
            ])
            ->where('ID', $PULL_OUT_ID)->get();

        return $result;
    }
    public function getPullOutItemsJournal(int $PULL_OUT_ID)
    {
        $result = PullOutItems::query()
            ->select([
                'ID',
                'ASSET_ACCOUNT_ID as ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw('1 as ENTRY_TYPE'),
                DB::raw("'DESTACCOUNT' as EXTENDED_OPTIONS")
            ])
            ->where('PULL_OUT_ID', $PULL_OUT_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
}
