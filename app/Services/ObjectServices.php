<?php

namespace App\Services;

use App\Models\ObjectTypeMap;

class ObjectServices
{

    public function ObjectNextID(string $TABLE_NAME): int
    {
        $Nxt_ID = 0;

        // Retrieving data from the database
        $result = ObjectTypeMap::where('TABLE_NAME', $TABLE_NAME)->first();

        if ($result) {
            $Nxt_ID = $result->NEXT_ID;
        } else {
            // Displaying a message if the table is not found
            dd("$TABLE_NAME table not found");
        }

        // Updating the NEXT_ID in the database
        ObjectTypeMap::where('TABLE_NAME', $TABLE_NAME)->update([
            'NEXT_ID' => ($Nxt_ID + 1)
        ]);

        return $Nxt_ID;
    }
    public function RecordLimit(): int
    {
        return 1000;
    }
}
