<?php

namespace App\Services;

use App\Models\DocumentStatus;

class DocumentStatusServices
{

    public function getDesc(int $ID): string
    {
        return DocumentStatus::where('ID', $ID)->first()->DESCRIPTION;
    }

}