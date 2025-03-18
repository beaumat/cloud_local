<?php

namespace App\Livewire\Import;

use App\Imports\ExcelDataImport;
use App\Services\LocationServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;


#[Title('Import Xero - Transaction Account')]
class XeroImportForm extends Component
{


    use WithFileUploads;
    public $file = null;

    public $data = [];
    public $locationList = [];
    public $locationid = 0;
    private $locationServices;
    public function boot(LocationServices $locationServices)
    {
        $this->locationServices = $locationServices;
    }
    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2548',
        ]);

        // Read the file without inserting into the database
        $import = new ExcelDataImport();
        Excel::import($import, $this->file->getRealPath());

        $this->data = $import->data; // Store the imported data in the component

        session()->flash('message', 'Excel data imported successfully!');
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
    }
    public function render()
    {
        return view('livewire.import.xero-import-form');
    }
}
