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
            $manager = new ImageManager(new Driver());
            $img = $manager->read($absolutePath);  // get actual image
            $img->crop(400, 200, 0, 0); // crop image
            $resizedPath = 'resized_' . basename($path);
            $img->save(public_path('storage/images/qrcode/' . $resizedPath)); // save in qrcode folder

            $qrcode = new QrReader(public_path('storage/images/qrcode/' . $resizedPath)); // reading qr-code
            $codeGenerate = $qrcode->text();

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
