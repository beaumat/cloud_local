<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Component;
use Livewire\WithFileUploads;
use Zxing\QrReader;

class HemoUploadFileModal extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $images = [];
    public $qrCodeData = [];
    public $qrCodeNotReadData = [];
    private $hemoServices;
    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }
    public function openModal()
    {
        $this->qrCodeNotReadData = [];
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function uploadImages()
    {
        $this->qrCodeNotReadData = [];
        $this->validate([
            'images.*' => 'image|max:1024', // 1MB Max per image
        ]);

        foreach ($this->images as $image) {
            
            // Store the image
            $path = $image->store('images', 'custom_local');
            // Get the absolute path to the stored image
            $absolutePath = (string) public_path('storage/' . $path);

            $qrcode = new QrReader($absolutePath);
            $codeGenerate = $qrcode->text(); 
            // Store QR code data along with just the filename
            $this->qrCodeData[] = [
                'code' => $codeGenerate,
                'filename' => basename($path),
                'filepath' =>  $path
            ];
        }
        // Reading

        $gotReadDoc = (bool) false;
        foreach ($this->qrCodeData as $list) {
            $gotReadDoc = true;
            $gotInsert =  $this->hemoServices->UpdateQRFile($list['code'], $list['filename'], $list['filepath']);
            if ($gotInsert) {
                $this->qrCodeNotReadData[] = [
                    'code' =>  $list['code'],
                    'status' => true
                ];
            } else {
                $this->qrCodeNotReadData[] = [
                    'code' =>  $list['code'],
                    'status' => false
                ];
            }
        }
        if ($gotReadDoc == false) {
            $this->qrCodeNotReadData[] = [
                'code' => 'No file',
                'status' => false
            ];
        }


        $this->reset(['qrCodeData', 'images']);
    }
    public function render()
    {
        return view('livewire.hemodialysis.hemo-upload-file-modal');
    }
}
