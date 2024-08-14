<?php

namespace App\Services;

use App\Models\AccountJournal;
use Illuminate\Support\Facades\DB;

class AccountJournalServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    private function Update(int $ACCOUNT_ID, int $LOCATION_ID, int $SUBSIDIARY_ID, int $SEQUENCE_GROUP, int $OBJECT_TYPE, int $OBJECT_ID, string $OBJECT_DATE, int $ENTRY_TYPE, float $AMOUNT, $EXTENDED_OPTIONS = null)
    {

        if ($ACCOUNT_ID > 0) {

            AccountJournal::where('LOCATION_ID', $LOCATION_ID)
                ->where('OBJECT_TYPE', $OBJECT_TYPE)
                ->where('OBJECT_ID', $OBJECT_ID)
                ->where('OBJECT_DATE', $OBJECT_DATE)
                ->where('SUBSIDIARY_ID', $SUBSIDIARY_ID)
                ->where('EXTENDED_OPTIONS', $EXTENDED_OPTIONS)
                ->update([
                    'SEQUENCE_GROUP'    => $SEQUENCE_GROUP,
                    'ACCOUNT_ID'        => $ACCOUNT_ID,
                    'ENTRY_TYPE'        => $ENTRY_TYPE,
                    'AMOUNT'            => $AMOUNT,
                ]);
        } else {
            AccountJournal::where('LOCATION_ID', $LOCATION_ID)
                ->where('OBJECT_TYPE', $OBJECT_TYPE)
                ->where('OBJECT_ID', $OBJECT_ID)
                ->where('OBJECT_DATE', $OBJECT_DATE)
                ->where('SUBSIDIARY_ID', $SUBSIDIARY_ID)
                ->where('EXTENDED_OPTIONS', $EXTENDED_OPTIONS)
                ->update([
                    'SEQUENCE_GROUP'    => $SEQUENCE_GROUP,
                    'ENTRY_TYPE'        => $ENTRY_TYPE,
                    'AMOUNT'            => $AMOUNT
                ]);
        }
    }
    private function Store(int $PREVIOUS_ID, int $SEQUENCE_NO, int $JOURNAL_NO, int $ACCOUNT_ID, int $LOCATION_ID, int $SUBSIDIARY_ID, int $SEQUENCE_GROUP, int $OBJECT_TYPE, int $OBJECT_ID, string $OBJECT_DATE, int $ENTRY_TYPE, float $AMOUNT, float $ENDING_BALANCE, $EXTENDED_OPTIONS = null)
    {

        $ID = (int) $this->object->ObjectNextID('ACCOUNT_JOURNAL');
        AccountJournal::create([
            'ID' => $ID,
            'PREVIOUS_ID'    => $PREVIOUS_ID > 0 ? $PREVIOUS_ID : null,
            'SEQUENCE_NO'    => $SEQUENCE_NO,
            'JOURNAL_NO'     => $JOURNAL_NO,
            'ACCOUNT_ID'     => $ACCOUNT_ID,
            'LOCATION_ID'    => $LOCATION_ID,
            'SUBSIDIARY_ID'  => $SUBSIDIARY_ID,
            'SEQUENCE_GROUP' => $SEQUENCE_GROUP,
            'OBJECT_TYPE'    => $OBJECT_TYPE,
            'OBJECT_ID'      => $OBJECT_ID,
            'OBJECT_DATE'    => $OBJECT_DATE,
            'ENTRY_TYPE'     => $ENTRY_TYPE,
            'AMOUNT'         => $AMOUNT,
            'ENDING_BALANCE' => $ENDING_BALANCE,
            'EXTENDED_OPTIONS' => $EXTENDED_OPTIONS
        ]);
    }
    public function getJournalNo(int $OBJECT_TYPE, int $OBJECT_ID): int
    {
        $data = AccountJournal::query()
            ->select(['JOURNAL_NO'])
            ->where('OBJECT_TYPE', $OBJECT_TYPE)
            ->where('OBJECT_ID', $OBJECT_ID)
            ->first();




        if ($data) { // if exists
            return (int) $data->JOURNAL_NO;
        }

        return  (int) AccountJournal::max('JOURNAL_NO');
    }

    public function getRecord(int $OBJECT_TYPE, int $OBJECT_ID): int
    {
        $data = AccountJournal::query()
            ->select(['JOURNAL_NO'])
            ->where('OBJECT_TYPE', $OBJECT_TYPE)
            ->where('OBJECT_ID', $OBJECT_ID)
            ->first();

        if ($data) { // if exists
            return (int) $data->JOURNAL_NO;
        }

        return 0;
    }
    private function getPreviousID(int $ACCOUNT_ID, int $LOCATION_ID): int
    {
        $result = DB::table('account_journal')
            ->select(['ID'])
            ->where('ACCOUNT_ID', $ACCOUNT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->orderBy('ID', 'desc')
            ->limit(1)
            ->first();

        if ($result) {
            return $result->ID ?? 0;
        }
        return 0;
    }

    private function getEndingLastOutPut(int $ACCOUNT_ID, int $LOCATION_ID, string $OBJECT_DATE)
    {
        $result = DB::table('account_journal')
            ->select(['SEQUENCE_NO', 'ENDING_BALANCE'])
            ->where('ACCOUNT_ID', $ACCOUNT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('OBJECT_DATE', '<=', $OBJECT_DATE)
            ->orderBy('OBJECT_DATE', 'desc')
            ->orderBy('ID', 'desc')
            ->limit(1)
            ->first();

        if ($result) {
            return [
                'SEQUENCE_NO' => $result->SEQUENCE_NO,
                'ENDING_BALANCE' => $result->ENDING_BALANCE
            ];
        }

        return [
            'SEQUENCE_NO' => -1,
            'ENDING_BALANCE' => 0
        ];
    }

    public function getSumDebitCredit(int $JOURNAL_NO)
    {
        $result = AccountJournal::query()
            ->select([
                DB::raw('SUM(IF(ENTRY_TYPE=0, AMOUNT, 0)) as DEBIT'),
                DB::raw('SUM(IF(ENTRY_TYPE=1, AMOUNT, 0)) as CREDIT')
            ])
            ->where('ACCOUNT_JOURNAL.JOURNAL_NO', $JOURNAL_NO)
            ->first();

        if ($result) {

            return [
                'DEBIT' => $result->DEBIT,
                'CREDIT' => $result->CREDIT
            ];
        }

        return [
            'DEBIT' => 0,
            'CREDIT' => 0
        ];
    }
    private function JournalExists(int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $LOCATION_ID, int $SUBSIDIARY_ID, string $EXTENDED_OPTIONS): bool
    {
        $result = (bool) AccountJournal::query()
            ->where('OBJECT_ID', $OBJECT_ID)
            ->where('OBJECT_TYPE', $OBJECT_TYPE)
            ->where('OBJECT_DATE', $OBJECT_DATE)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SUBSIDIARY_ID', $SUBSIDIARY_ID)
            ->where('EXTENDED_OPTIONS', $EXTENDED_OPTIONS)
            ->exists();

        return $result;
    }

    public function JournalModify(int $ACCOUNT_ID, int $LOCATION_ID, int $JOURNAL_NO, int $SUBSIDIARY_ID, int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $ENTRY_TYPE, float $AMOUNT, int  $SEQUENCE_GROUP, string $EXTENDED_OPTIONS)
    {
        if (!$this->JournalExists($OBJECT_ID, $OBJECT_TYPE, $OBJECT_DATE, $LOCATION_ID, $SUBSIDIARY_ID, $EXTENDED_OPTIONS)) {

            if ($ACCOUNT_ID  ==  0) {
                return;
            }

            $PREV_ID = (int) $this->getPreviousID($ACCOUNT_ID, $LOCATION_ID);
            $ENDING = $this->getEndingLastOutPut($ACCOUNT_ID, $LOCATION_ID, $OBJECT_DATE);
            $SEQUENCE_NO = (int) $ENDING['SEQUENCE_NO'];
            $ENDING_BALANCE = 0;

            if ($ENTRY_TYPE == 0) {
                $ENDING_BALANCE = (float) $ENDING['ENDING_BALANCE'] + $AMOUNT;
            } else {
                $ENDING_BALANCE = (float) $ENDING['ENDING_BALANCE'] - $AMOUNT;
            }

            $this->Store($PREV_ID, $SEQUENCE_NO + 1, $JOURNAL_NO, $ACCOUNT_ID, $LOCATION_ID, $SUBSIDIARY_ID, $SEQUENCE_GROUP, $OBJECT_TYPE, $OBJECT_ID, $OBJECT_DATE, $ENTRY_TYPE, $AMOUNT, $ENDING_BALANCE, $EXTENDED_OPTIONS);
            return;
        }

        $this->Update($ACCOUNT_ID, $LOCATION_ID, $SUBSIDIARY_ID, $SEQUENCE_GROUP, $OBJECT_TYPE, $OBJECT_ID, $OBJECT_DATE, $ENTRY_TYPE, $AMOUNT, $EXTENDED_OPTIONS);
        // no more textended function

    }
    public function JournalExecute(int $JOURNAL_NO, $data, int $LOCATION_ID, int $OBJECT_TYPE, string $OBJECT_DATE, string $EXTENDED = '')
    {
        foreach ($data as $list) {
            $OBJECT_ID = (int) $list->ID;
            $ACCOUNT_ID = (int) $list->ACCOUNT_ID;
            $SUBSIDIARY_ID = (int) $list->SUBSIDIARY_ID;
            $ENTRY_TYPE = (int) $list->ENTRY_TYPE;
            $AMOUNT = (float) $list->AMOUNT;
            $SEQUENCE_GROUP = 0;
            $EXTENDED_OPTIONS = $EXTENDED;

            // if (isset($list->EXTENDED_OPTIONS)) {
            //     $EXTENDED_OPTIONS = $list->EXTENDED_OPTIONS;
            //     // Perform any additional operations if EXTENDED_OPTIONS is set
            // }

            if (isset($list->SEQUENCE_GROUP)) {
                $SEQUENCE_GROUP = $list->SEQUENCE_GROUP;
                // Perform any additional operations if EXTENDED_OPTIONS is set
            }

            $this->JournalModify($ACCOUNT_ID, $LOCATION_ID, $JOURNAL_NO, $SUBSIDIARY_ID, $OBJECT_ID, $OBJECT_TYPE, $OBJECT_DATE, $ENTRY_TYPE, $AMOUNT, $SEQUENCE_GROUP, $EXTENDED_OPTIONS);
        }
    }
    public function getJournalList(int $JOURNAL_NO)
    {
        $result = AccountJournal::query()
            ->select([
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                DB::raw(" if(account_journal.ENTRY_TYPE = 0, account_journal.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(account_journal.ENTRY_TYPE = 1, account_journal.AMOUNT, '' ) as CREDIT ")
            ])->leftJoin('account as a', 'a.ID', '=', 'account_journal.ACCOUNT_ID')
            ->where('account_journal.JOURNAL_NO', $JOURNAL_NO)
            ->get();

        return $result;
    }
}
