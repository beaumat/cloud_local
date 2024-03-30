<?php

namespace App\Services;

use App\Models\NursesNotes;
use Illuminate\Support\Carbon;

class NursesNotesServices
{

    private $object;
    private $compute;
    private $locationReference;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;

    }
    public function Save(
        int $HEMO_ID,
        string $TIME,
        float $BP_1,
        float $BP_2,
        float $HR,
        float $BFR,
        float $AP,
        float $VP,
        float $TFR,
        float $TMP,
        float $HEPARIN,
        float $FLUSHING,
        string $NOTES
    ) {
        $LINE_NO = (int) NursesNotes::where('HEMO_ID', $HEMO_ID)->max('LINE_NO');
        $ID = (int) $this->object->ObjectNextID('NURSES_NOTES');

        NursesNotes::create([
            'ID' => $ID,
            'RECORDED_ON' => Carbon::now(),
            'HEMO_ID' => $HEMO_ID,
            'USER_ID' => Auth()->user()->id,
            'LINE_NO' => $LINE_NO + 1,
            'TIME' => $TIME,
            'BP_1' => $BP_1,
            'BP_2' => $BP_2,
            'HR' => $HR,
            'BFR' => $BFR,
            'AP' => $AP,
            'VP' => $VP,
            'TFR' => $TFR,
            'TMP' => $TMP,
            'HEPARIN' => $HEPARIN,
            'FLUSHING' => $FLUSHING,
            'NOTES' => $NOTES
        ]);
    }
    public function Update(
        int $ID,
        string $TIME,
        float $BP_1,
        float $BP_2,
        float $HR,
        float $BFR,
        float $AP,
        float $VP,
        float $TFR,
        float $TMP,
        float $HEPARIN,
        float $FLUSHING,
        string $NOTES
    ) {

        NursesNotes::where('ID', $ID)->update([
            'TIME' => $TIME,
            'BP_1' => $BP_1,
            'BP_2' => $BP_2,
            'HR' => $HR,
            'BFR' => $BFR,
            'AP' => $AP,
            'VP' => $VP,
            'TFR' => $TFR,
            'TMP' => $TMP,
            'HEPARIN' => $HEPARIN,
            'FLUSHING' => $FLUSHING,
            'NOTES' => $NOTES
        ]);
    }
    public function Delete(int $ID)
    {
        NursesNotes::where('ID', $ID)->delete();
    }
    public function DeleteAll(int $HEMO_ID)
    {

        NursesNotes::where('HEMO_ID', $HEMO_ID);
    }
    public function List(int $HEMO_ID)
    {
        return NursesNotes::query()
            ->select([
                'ID',
                'TIME',
                'BP_1',
                'BP_2',
                'HR',
                'BFR',
                'AP',
                'VP',
                'TFR',
                'TMP',
                'HEPARIN',
                'FLUSHING',
                'NOTES'
            ])
            ->where('HEMO_ID', $HEMO_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

    }

}