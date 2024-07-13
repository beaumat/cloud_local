<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Component;
use Livewire\WithFileUploads;
use Zxing\QrReader;
// use Intervention\Image\Laravel\Facades\Image;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

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
    private function ImageAdjust()
    {
    }
    public function uploadImages()
    {
        $this->qrCodeNotReadData = [];
        $this->validate([
            'images.*' => 'image|max:1024', // 1MB Max per image
        ]);

        foreach ($this->images as $list) {

            // Store the image
            $path = $list->store('images', 'custom_local');
            // Get the absolute path to the stored image
            $absolutePath = (string) public_path('storage/' . $path);

            $manager = new ImageManager(new Driver());
            $img = $manager->read($absolutePath);
            $img->crop(200, 150, 45, 90);
            // Resize the image to 1/4th of its original size
            // $img = Image::make($absolutePath);
            // $img->resize($img->width() / 4, $img->height() / 4);

            // Save the resized image temporarily
            $resizedPath = 'images/resized_' . basename($path);
            $img->save(public_path('storage/qrcode' . $resizedPath));

            // Read the QR code from the resized image
            $qrcode = new QrReader(public_path('storage/' . $resizedPath));
            $codeGenerate = $qrcode->text();


            // $qrcode = new QrReader($absolutePath);
            // $codeGenerate = $qrcode->text();
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
