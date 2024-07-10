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
        $tempPath = $PDF->store('public/temp', 'public');
        $randomFilename = Str::random(40);
        $extension = $PDF->extension();
        $newPath = 'payment/' . $randomFilename . '.' . $extension;

        Storage::disk('public')->move($tempPath, $newPath);

        $dataReturn = [
            'new_path' => $newPath,
            'extension' => $extension,
            'filename' => $randomFilename
        ];

        return $dataReturn;
    }
    public function Treatment($Image)
    {
        $tempPath = $Image->store('public/temp', 'public');

        $randomFilename = Str::random(40);
        $extension = $Image->extension();
        $newPath = 'treatment/' . $randomFilename . '.' . $extension; // Adjusted path
        Storage::disk('public')->move($tempPath, $newPath);
        $dataReturn = [
            'new_path' => $newPath,
            'extension' => $extension,
            'filename' => $randomFilename
        ];

        return $dataReturn;
    }



}