<?php

namespace App\Services;

use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetails;
use Illuminate\Support\Facades\DB;

class GeneralJournalServices
{
    private $dateServices;
    private $systemSettingServices;
    private $object;
    public function __construct(ObjectServices $objectServices, SystemSettingServices $systemSettingServices, DateServices $dateServices)
    {
        $this->object = $objectServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices = $dateServices;
    }
    public function Store(string $DATE, string $CODE, int $LOCATION_ID, bool $ADJUSTING_ENTRY, string $NOTES): int
    {

        $ID = (int) $this->object->ObjectNextID('GENERAL_JOURNAL');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('GENERAL_JOURNAL');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        GeneralJournal::create([
            'ID' => $ID,
            'DATE' => $DATE,
            'RECORDED_ON' => $this->dateServices->Now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'LOCATION_ID' => $LOCATION_ID,
            'ADJUSTING_ENTRY' => $ADJUSTING_ENTRY,
            'NOTES' => $NOTES,
            'STATUS' => 0,
            'STRATUS_DATE' => $this->dateServices->NowDate()
        ]);

        return $ID;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        GeneralJournal::where('ID', $ID)
            ->update([
                'STATUS' => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate()
            ]);
    }
    public function Update(int $ID, string $CODE, int $LOCATION_ID, bool $ADJUSTING_ENTRY, string $NOTES)
    {
        GeneralJournal::where('ID', $ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->update([
                'CODE' => $CODE,
                'ADJUSTING_ENTRY' => $ADJUSTING_ENTRY,
                'NOTES' => $NOTES
            ]);
    }
    public function Delete(int $ID)
    {
        GeneralJournalDetails::where('GENERAL_JOURNAL_ID', $ID)->delete();
        GeneralJournal::where('ID', $ID)->delete();
    }
    public function Get(int $ID)
    {
        return GeneralJournal::where('ID', $ID)->first();
    }
    public function Search($search, int $locationId, int $perPage)
    {
        $result = GeneralJournal::query()
            ->select([
                'general_journal.ID',
                'general_journal.CODE',
                'general_journal.DATE',
                'general_journal.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',

            ])
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'general_journal.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'general_journal.STATUS')
            ->when($search, function ($query) use (&$search) {
                $query->where('general_journal.CODE', 'like', '%' . $search . '%')
                    ->orWhere('general_journal.NOTES', 'like', '%' . $search . '%');
            })
            ->orderBy('general_journal.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }

    private function getLine($Id): int
    {
        return (int) GeneralJournalDetails::where('GENERAL_JOURNAL_ID', $Id)->max('LINE_NO');
    }
    public function StoreDetails(int $GENERAL_JOURNAL_ID, int $ACCOUNT_ID, float $DEBIT, float $CREDIT, string $NOTES, int $CLASS_ID)
    {

        $ENTRY_TYPE = 0;
        if ($CREDIT != 0) {
            $ENTRY_TYPE = 1;
        }
        $ID = (int) $this->object->ObjectNextID('GENERAL_JOURNAL_DETAILS');
        $LINE_NO = (int) $this->getLine($GENERAL_JOURNAL_ID) + 1;

        GeneralJournalDetails::create([
            'ID' => $ID,
            'GENERAL_JOURNAL_ID' => $GENERAL_JOURNAL_ID,
            'LINE_NO' => $LINE_NO,
            'ACCOUNT_ID' => $ACCOUNT_ID,
            'ENTRY_TYPE' => $ENTRY_TYPE,
            'DEBIT' => $DEBIT,
            'CREDIT' => $CREDIT,
            'AMOUNT' => $ENTRY_TYPE == 0 ? $DEBIT : $CREDIT,
            'NOTES' => $NOTES,
            'CLASS_ID' => $CLASS_ID > 0 ? $CLASS_ID : null
        ]);
    }
    public function UpdateDetails(int $ID, int $GENERAL_JOURNAL_ID, int $ACCOUNT_ID, float $DEBIT, float $CREDIT, string $NOTES, int $CLASS_ID)
    {

        $ENTRY_TYPE = 0;
        if ($CREDIT != 0) {
            $ENTRY_TYPE = 1;
        }
        GeneralJournalDetails::where('ID', $ID)
            ->where('GENERAL_JOURNAL_ID', $GENERAL_JOURNAL_ID)
            ->where('ACCOUNT_ID', $ACCOUNT_ID)
            ->update([
                'ENTRY_TYPE' => $ENTRY_TYPE,
                'DEBIT' => $DEBIT,
                'CREDIT' => $CREDIT,
                'AMOUNT' => $ENTRY_TYPE == 0 ? $DEBIT : $CREDIT,
                'NOTES' => $NOTES,
                'CLASS_ID' => $CLASS_ID > 0 ? $CLASS_ID : null
            ]);
    }
    public function DeleteDetails(int $ID)
    {
        $data = $this->getDetails($ID);
        if ($data) {
            GeneralJournalDetails::where('ID', $data->ID)
                ->where('GENERAL_JOURNAL_ID', $data->GENERAL_JOURNAL_ID)
                ->where('ACCOUNT_ID', $data->ACCOUNT_ID)
                ->delete();
        }
    }
    public function ListDetails(int $GENERAL_JOURNAL_ID)
    {
        $result = GeneralJournalDetails::query()
            ->select([
                'general_journal_details.ID',
                'general_journal_details.ACCOUNT_ID',
                'general_journal_details.DEBIT',
                'general_journal_details.CREDIT',
                'general_journal_details.NOTES',
                'general_journal_details.CLASS_ID',
                'account.NAME as ACCOUNT_DESCRIPTION',
                'account.TAG as CODE',
                'class.NAME as CLASS_NAME'

            ])
            ->leftJoin('account', 'account.ID', '=', 'general_journal_details.ACCOUNT_ID')
            ->leftJoin('class', 'class.ID', '=', 'general_journal_details.CLASS_ID')
            ->where('general_journal_details.GENERAL_JOURNAL_ID', $GENERAL_JOURNAL_ID)->get();

        return $result;
    }


    public function GetTotal(int $GENERAL_JOURNAL_ID)
    {

        $result = GeneralJournalDetails::query()
            ->select([
                DB::raw('ifnull(sum(DEBIT),0) as TOTAL_DEBIT'),
                DB::raw('ifnull(sum(CREDIT),0) as TOTAL_CREDIT')
            ])
            ->where('GENERAL_JOURNAL_ID', $GENERAL_JOURNAL_ID)
            ->first();

        if ($result) {
            return [
                'TOTAL_DEBIT' => $result->TOTAL_DEBIT,
                'TOTAL_CREDIT' => $result->TOTAL_CREDIT,
            ];
        }

        return [
            'TOTAL_DEBIT' => 0,
            'TOTAL_CREDIT' => 0,
        ];

    }
    public function getDetails(int $Id)
    {
        return GeneralJournalDetails::where('ID', $Id)->first();
    }

    public function getGeneralJournalEntries(int $ID)
    {
        $result = GeneralJournalDetails::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                \DB::raw('0 as SUBSIDIARY_ID'),
                'AMOUNT',
                'ENTRY_TYPE'
            ])
            ->where('GENERAL_JOURNAL_ID', $ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
}