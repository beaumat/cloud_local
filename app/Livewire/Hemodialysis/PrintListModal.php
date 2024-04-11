<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;
use Redirect;

class PrintListModal extends Component
{
    use WithPagination;
    #[Reactive]
    public int $LOCATION_ID;
    public $hemoSelected = [];
    public $dataList = [];
    public $hemoList = [];
    public bool $showModal;
    public string $search = '';
    public string $id;
    private $hemoServices;
    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }
    public function print()
    {
        $gotSelect = false;
        $this->id = "";
        foreach ($this->hemoSelected as $hemoId => $isSelected) {

            if ($isSelected) {
                try {
                    $gotSelect = true;
                    if ($this->id == "") {
                        $this->id = $hemoId;
                    } else {
                        $this->id = $this->id . "," . $hemoId;
                    }

                } catch (\Throwable $th) {

                    return;
                }
            }

        }

        if (!$gotSelect) {
            return;
        }
        // 
        // return Redirect::to(route('transactionshemo_print', ['id' => $this->id]));

        $url = route('transactionshemo_print', ['id' => $this->id]);
        $this->dispatch('openNewTab', data: $url);
        $this->closeModal();

    }
    public function mount()
    {

    }
    public function openModal()
    {
        $this->showModal = true;
    }
    #[On('print-list-modal-close')]
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        $this->hemoList = $this->hemoServices->SearchList($this->search, $this->LOCATION_ID);

        return view('livewire.hemodialysis.print-list-modal');
    }
}
