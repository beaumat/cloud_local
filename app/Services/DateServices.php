<?php

namespace App\Services;

class DateServices
{

    public function WeeklyList(): array
    {
        return [
            ['ID' => 1, 'NAME' => 'Monday'],
            ['ID' => 2, 'NAME' => 'Tuesday'],
            ['ID' => 3, 'NAME' => 'Wednesday'],
            ['ID' => 4, 'NAME' => 'Thursday'],
            ['ID' => 5, 'NAME' => 'Friday'],
            ['ID' => 6, 'NAME' => 'Saturday'],
            ['ID' => 7, 'NAME' => 'Sunday'],
        ];
    }

    public function SemiMonthly(): array
    {
        return [
            ['ID' => 1, 'NAME' => '1/16 th'],
            ['ID' => 2, 'NAME' => '2/17 th'],
            ['ID' => 3, 'NAME' => '3/18 th'],
            ['ID' => 4, 'NAME' => '4/19 th'],
            ['ID' => 5, 'NAME' => '5/20 th'],
            ['ID' => 6, 'NAME' => '6/21 th'],
            ['ID' => 7, 'NAME' => '7/22 th'],
            ['ID' => 8, 'NAME' => '8/23 th'],
            ['ID' => 9, 'NAME' => '9/24 th'],
            ['ID' => 10, 'NAME' => '10/25 th'],
            ['ID' => 11, 'NAME' => '11/26 th'],
            ['ID' => 12, 'NAME' => '12/27 th'],
            ['ID' => 13, 'NAME' => '13/28 th'],
            ['ID' => 14, 'NAME' => '14/29 th'],
            ['ID' => 15, 'NAME' => '15/30 th'],
        ];
    }

    public function DayList(): array
    {
        return [
            ['ID' => 1, 'NAME' => '1st'],
            ['ID' => 2, 'NAME' => '2nd'],
            ['ID' => 3, 'NAME' => '3rd'],
            ['ID' => 4, 'NAME' => '4th'],
            ['ID' => 5, 'NAME' => '5th'],
            ['ID' => 6, 'NAME' => '6th'],
            ['ID' => 7, 'NAME' => '7th'],
            ['ID' => 8, 'NAME' => '8th'],
            ['ID' => 9, 'NAME' => '9th'],
            ['ID' => 10, 'NAME' => '10th'],
            ['ID' => 11, 'NAME' => '11th'],
            ['ID' => 12, 'NAME' => '12th'],
            ['ID' => 13, 'NAME' => '13th'],
            ['ID' => 14, 'NAME' => '14th'],
            ['ID' => 15, 'NAME' => '15th'],
            ['ID' => 16, 'NAME' => '16th'],
            ['ID' => 17, 'NAME' => '17th'],
            ['ID' => 18, 'NAME' => '18th'],
            ['ID' => 19, 'NAME' => '19th'],
            ['ID' => 20, 'NAME' => '20th'],
            ['ID' => 21, 'NAME' => '21th'],
            ['ID' => 22, 'NAME' => '22th'],
            ['ID' => 23, 'NAME' => '23th'],
            ['ID' => 24, 'NAME' => '24th'],
            ['ID' => 25, 'NAME' => '25th'],
            ['ID' => 26, 'NAME' => '26th'],
            ['ID' => 27, 'NAME' => '27th'],
            ['ID' => 28, 'NAME' => '28th'],
            ['ID' => 29, 'NAME' => '29th'],
            ['ID' => 30, 'NAME' => '30th'],

        ];
    }
    public function SemiAnnual(): array
    {
        return [
            ['ID' => 1, 'NAME' => 'Jan/Jul'],
            ['ID' => 2, 'NAME' => 'Feb/Aug'],
            ['ID' => 3, 'NAME' => 'Mar/Sep'],
            ['ID' => 4, 'NAME' => 'Apr/Oct'],
            ['ID' => 5, 'NAME' => 'May/Nov'],
            ['ID' => 6, 'NAME' => 'Jun/Dec'],

        ];
    }
    public function MonthList(): array
    {
        return [
            ['ID' => 1, 'NAME' => 'Jan'],
            ['ID' => 2, 'NAME' => 'Feb'],
            ['ID' => 3, 'NAME' => 'Mar'],
            ['ID' => 4, 'NAME' => 'Apr'],
            ['ID' => 5, 'NAME' => 'May'],
            ['ID' => 6, 'NAME' => 'Jun'],
            ['ID' => 7, 'NAME' => 'Jul'],
            ['ID' => 8, 'NAME' => 'Aug'],
            ['ID' => 9, 'NAME' => 'Sep'],
            ['ID' => 10, 'NAME' => 'Oct'],
            ['ID' => 11, 'NAME' => 'Nov'],
            ['ID' => 12, 'NAME' => 'Dec'],

        ];
    }
}
