<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\UploadServices;
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
    private $uploadServices;
    public function boot(HemoServices $hemoServices, UploadServices $uploadServices)
    {
        $this->hemoServices = $hemoServices;
        $this->uploadServices = $uploadServices;
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

            $data = $this->uploadServices->Treatment($image);

            // Read QR code from the stored image
            $qrcode = new QrReader(public_path('storage/' . $data['new_path'])); // Adjusted path usage
            $text = $qrcode->text();
            // Store QR code data along with just the filename
            $this->qrCodeData[] = [
                'code' => $text,
                'filename' => $data['filename'],
                'filepath' =>  $data['new_path']
            ];
        }

        foreach ($this->qrCodeData as $list) {
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
        $this->reset(['qrCodeData', 'images']);
    }
    public function render()
    {
        return view('livewire.hemodialysis.hemo-upload-file-modal');
    }
}
