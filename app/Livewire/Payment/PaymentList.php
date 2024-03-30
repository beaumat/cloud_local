<?php

namespace App\Livewire\Payment;

use App\Services\LocationServices;
use App\Services\PaymentServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

#[Title('Payments')]
class PaymentList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 20;
    public int $locationid;
    public $locationList = [];

    private $paymentServices;
    private $locationServices;
    private $userServices;

    public function boot(
        PaymentServices $paymentServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->paymentServices = $paymentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete($id)
    {
        try {

            $data = $this->paymentServices->get($id);

            if ($data) {

                if (Storage::disk('public')->exists($data->FILE_PATH)) {
                    Storage::disk('public')->delete($data->FILE_PATH);
                    // File has been successfully deleted
                }

                $this->paymentServices->Delete($data->ID);
                session()->flash('message', 'Successfully deleted.');

            }


        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        $dataList = $this->paymentServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.payment.payment-list', ['dataList' => $dataList]);
    }
}
