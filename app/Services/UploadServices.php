<?php

namespace App\Services;

use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UploadServices
{
    use WithFileUploads;
    public function RemoveIfExists($FILE_PATH)
    {
        if ($FILE_PATH) {
            if (Storage::disk('public')->exists($FILE_PATH)) {
                Storage::disk('public')->delete($FILE_PATH);
            }
        }
    }
    public function Payment($PDF)
    {
        $path = $PDF->store('payments', 'custom_local');
        $extension = $PDF->extension();
        $dataReturn = [
            'new_path' => $path,
            'extension' => $extension,
            'filename' => basename($path)
        ];
        return $dataReturn;
    }
    public function Treatment($Image)
    {

        try {

            $path = $Image->store('images', 'custom_local');
            $extension = $Image->extension();
    
            $dataReturn = [
                'new_path' =>  $path,
                'extension' => $extension,
                'filename' => basename($path)
            ];

            return $dataReturn;
        } catch (\Throwable $th) {
            //throw $th;
            dd($th->getMessage());
            return [];
        }
    }
}
