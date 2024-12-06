<?php

namespace App\Livewire\FixedAssetItem;

use App\Services\ItemServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Fixed Asset Items')]
class FixedAssetItemList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search' => ['except' => '']];
    public $search = '';
    public int $perPage = 40;
    private $itemServices;
    public function boot(ItemServices $itemServices)
    {
        $this->itemServices = $itemServices;
    }
    public function delete($id)
    {
        try {
            $this->itemServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        $items = $this->itemServices->SearchFixedAsset($this->search, $this->perPage);
        return view('livewire.fixed-asset-item.fixed-asset-item-list', ['items' => $items]);
    }
}
