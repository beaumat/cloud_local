<?php

namespace App\Services;

use App\Models\AccountJournal;

class AccountJournalServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }

    private function Store(
        int $PREVIOUS_ID,
        int $SEQUENCE_NO,
        int $JOURNAL_NO,
        int $ACCOUNT_ID,
        int $LOCATION_ID,
        int $SUBSIDIARY_ID,
        int $SEQUENCE_GROUP,
        int $OBJECT_TYPE,
        int $OBJECT_ID,
        string $OBJECT_DATE,
        int $ENTRY_TYPE,
        float $AMOUNT,
        float $ENDING_BALANCE,
        $EXTENDED_OPTIONS = null
    ) {

        $ID = (int) $this->object->ObjectNextID('ACCOUNT_JOURNAL');
        AccountJournal::create([
            'ID' => $ID,
            'PREVIOUS_ID' => $PREVIOUS_ID > 0 ? $PREVIOUS_ID : null,
            'SEQUENCE_NO' => $SEQUENCE_NO,
            'JOURNAL_NO' => $JOURNAL_NO,
            'ACCOUNT_ID' => $ACCOUNT_ID,
            'LOCATION_ID' => $LOCATION_ID,
            'SUBSIDIARY_ID' => $SUBSIDIARY_ID,
            'SEQUENCE_GROUP' => $SEQUENCE_GROUP,
            'OBJECT_TYPE' => $OBJECT_TYPE,
            'OBJECT_ID' => $OBJECT_ID,
            'OBJECT_DATE' => $OBJECT_DATE,
            'ENTRY_TYPE' => $ENTRY_TYPE,
            'AMOUNT' => $AMOUNT,
            'ENDING_BALANCE' => $ENDING_BALANCE,
            'EXTENDED_OPTIONS' => $EXTENDED_OPTIONS
        ]);
    }
    public function getJournalNo(int $OBJECT_TYPE, int $OBJECT_ID): int
    {
        $data = AccountJournal::query()
            ->select('JOURNAL_NO')
            ->where('OBJECT_TYPE', $OBJECT_TYPE)
            ->where('OBJECT_ID', $OBJECT_ID)
            ->first();

        if ($data) {
            return (int) $data->JOURNAL_NO;
        }
        return 0;
    }
    private function getPreviousAccountJournal(int $ACCOUNT_ID, int $LOCATION_ID, int $SUBSIDIARY_ID, int $SEQUENCE_GROUP, string $OBJECT_DATE)
    {
        // Define the subquery to get the maximum SEQUENCE_NO
        $currentJournal = \DB::table('ACCOUNT_JOURNAL')
            ->select('ACCOUNT_ID', 'LOCATION_ID', 'SUBSIDIARY_ID', 'SEQUENCE_GROUP', \DB::raw('MAX(SEQUENCE_NO) as SEQUENCE_NO'))
            ->where('ACCOUNT_ID', $ACCOUNT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SUBSIDIARY_ID', $SUBSIDIARY_ID) //from item_ID
            ->where('SEQUENCE_GROUP', $SEQUENCE_GROUP)
            ->where('OBJECT_DATE', '<=', $OBJECT_DATE)
            ->groupBy('ACCOUNT_ID', 'LOCATION_ID', 'SUBSIDIARY_ID', 'SEQUENCE_GROUP');

        // Main query
        $result = \DB::table('ACCOUNT_JOURNAL as aj')
            ->joinSub($currentJournal, 'cj', function ($join) {
                $join->on('aj.ACCOUNT_ID', '=', 'cj.ACCOUNT_ID')
                    ->on('aj.LOCATION_ID', '=', 'cj.LOCATION_ID')
                    ->on('aj.SUBSIDIARY_ID', '=', 'cj.SUBSIDIARY_ID')
                    ->on('aj.SEQUENCE_GROUP', '=', 'cj.SEQUENCE_GROUP')
                    ->on('aj.SEQUENCE_NO', '=', 'cj.SEQUENCE_NO');
            })
            ->leftJoin('ACCOUNT_JOURNAL as next_journal', 'next_journal.PREVIOUS_ID', '=', 'aj.ID')
            ->select('aj.ID', 'next_journal.ID as NEXT_ID', 'next_journal.AMOUNT as NEXT_AMOUNT')
            ->first();

        if ($result) {
            return [
                'ID' => $result->ID,
                'NEXT_ID' => $result->NEXT_ID,
                'NEXT_AMOUNT' => $result->NEXT_AMOUNT
            ];
        }
        return [
            'ID' => 0,
            'NEXT_ID' => 0,
            'NEXT_AMOUNT' => 0
        ];

    }

    private function getEnding(int $ID)
    {
        $result = AccountJournal::query()
            ->select(['SEQUENCE_NO', 'ENDING_BALANCE'])
            ->where('ID', $ID)->first();
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
            ->select(
                \DB::raw('SUM(IF(ENTRY_TYPE=0, AMOUNT, 0)) as DEBIT'),
                \DB::raw('SUM(IF(ENTRY_TYPE=1, AMOUNT, 0)) as CREDIT')
            )
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
    private function JournalExists($ACCOUNT_ID, $OBJECT_ID, $OBJECT_TYPE, $OBJECT_DATE, $LOCATION_ID, $ENTRY_TYPE): bool
    {
        return AccountJournal::query()
            ->where('ACCOUNT_ID', $ACCOUNT_ID)
            ->where('OBJECT_ID', $OBJECT_ID)
            ->where('OBJECT_TYPE', $OBJECT_TYPE)
            ->where('OBJECT_DATE', $OBJECT_DATE)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('ENTRY_TYPE', $ENTRY_TYPE)
            ->exists();

    }
    public function JournalExecute(int $JOURNAL_NO, $data, int $LOCATION_ID, int $OBJECT_TYPE, string $OBJECT_DATE)
    {
        foreach ($data as $list) {
            $OBJECT_ID = (int) $list->ID;
            $ACCOUNT_ID = (int) $list->ACCOUNT_ID;
            $SUBSIDIARY_ID = (int) $list->SUBSIDIARY_ID;
            $ENTRY_TYPE = (int) $list->ENTRY_TYPE;
            $AMOUNT = (float) $list->AMOUNT;
            $SEQUENCE_GROUP = 0;
            
            if (isset($list->EXTENDED_OPTIONS)) {
                $EXTENDED_OPTIONS = $list->EXTENDED_OPTIONS;
                // Perform any additional operations if EXTENDED_OPTIONS is set
            } else {
                $EXTENDED_OPTIONS = null;
                // Perform any additional operations if EXTENDED_OPTIONS is not set
            }

            if (isset($list->SEQUENCE_GROUP)) {
                $SEQUENCE_GROUP = $list->SEQUENCE_GROUP;
                // Perform any additional operations if EXTENDED_OPTIONS is set
            } else {
                $SEQUENCE_GROUP = 0;
                // Perform any additional operations if EXTENDED_OPTIONS is not set
            }


            if (!$this->JournalExists($ACCOUNT_ID, $OBJECT_ID, $OBJECT_TYPE, $OBJECT_DATE, $LOCATION_ID, $ENTRY_TYPE)) {

                $PREV = $this->getPreviousAccountJournal($ACCOUNT_ID, $LOCATION_ID, $SUBSIDIARY_ID, 0, $OBJECT_DATE);
                $PREV_ID = (int) $PREV['ID'];
                $ENDING = $this->getEnding($PREV_ID);

                $SEQUENCE_NO = (int) $ENDING['SEQUENCE_NO'];
                $ENDING_BALANCE = 0;

                if ($ENTRY_TYPE == 0) {
                    $ENDING_BALANCE = (float) $ENDING['ENDING_BALANCE'] + $AMOUNT;
                } else {
                    $ENDING_BALANCE = (float) $ENDING['ENDING_BALANCE'] - $AMOUNT;
                }

                $this->Store(
                    $PREV_ID,
                    $SEQUENCE_NO + 1,
                    $JOURNAL_NO,
                    $ACCOUNT_ID,
                    $LOCATION_ID,
                    $SUBSIDIARY_ID,
                    $SEQUENCE_GROUP,
                    $OBJECT_TYPE,
                    $OBJECT_ID,
                    $OBJECT_DATE,
                    $ENTRY_TYPE,
                    $AMOUNT,
                    $ENDING_BALANCE,
                    $EXTENDED_OPTIONS
                );
            }



        }

    }
}
