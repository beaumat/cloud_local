<?php

namespace App\Livewire\Payment;

use App\Services\LocationServices;
use App\Services\PaymentServices;
use App\Services\UploadServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Payments')]
class PaymentList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 30;
    public int $locationid;
    public $locationList = [];
    private $paymentServices;
    private $locationServices;
    private $userServices;
    private $uploadServices;
    public function boot(
        PaymentServices $paymentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        UploadServices $uploadServices
    ) {
        $this->paymentServices = $paymentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->uploadServices = $uploadServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete(int $id)
    {
        try {
            $data = $this->paymentServices->get($id);
            if ($data) {

                if ($data->STATUS == 0) {
                    DB::beginTransaction();
                    $this->uploadServices->RemoveIfExists($data->FILE_PATH);
                    $this->paymentServices->Delete($data->ID);
                    session()->flash('message', 'Successfully deleted.');
                    DB::commit();
                    return;
                }

                session()->flash('error', 'Invalid. this file cannot be deleted.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function updatedlocationid()
    {

        try {
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
}
