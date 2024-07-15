<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Component;
use Livewire\WithFileUploads;
use Zxing\QrReader;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class HemoUploadFileModal extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $images = [];
    public $qrCodeData = [];
    public $qrCodeNotReadData = [];
    public $uploadProgress = 0;  // New property for upload progress

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

        foreach ($this->images as $list) {
            $path = $list->store('images', 'custom_local');
            $absolutePath = (string) public_path('storage/' . $path);

            $manager1 = new ImageManager(new Driver());
            $img = $manager1->read($absolutePath);  // get actual image
            $img->crop(400, 200, 0, 0); // crop image
            $cropPath = 'crop_' . basename($path);
            $crop_path  = public_path('storage/images/qrcode/' . $cropPath);
            $img->save($crop_path);

            // level 1  
            $qrcode = new QrReader($crop_path); // reading qr-code
            $codeGenerate = (string) $qrcode->text() ?? '';

            if ($codeGenerate == '') {
                // level 2
                $manager2 = new ImageManager(new Driver());
                $img2 = $manager2->read($crop_path);  // get actual image
                $img2->crop(600, 600, 600, 0); // crop image
                $img2->place($crop_path, 'top', 0, 200, 100);
                $topPath = 'top_' . basename($crop_path);
                $top_path  = public_path('storage/images/qrcode/' . $topPath);
                $img2->save($top_path);
                $qrcode = new QrReader($top_path); // reading qr-code
                $codeGenerate = (string) $qrcode->text() ?? '';
            }

            // save in qrcode folder
            if ($codeGenerate) {
                $this->qrCodeData[] = [
                    'code' => $codeGenerate,
                    'filename' => basename($path),
                    'filepath' =>  $path
                ];
            }
        }

        // Reading
        $gotReadDoc = false;

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
        $this->closeModal();
        $this->dispatch('refresh-list');
    }

    public function render()
    {
        return view('livewire.hemodialysis.hemo-upload-file-modal');
    }
}
