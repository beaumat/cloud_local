<?php

namespace App\Livewire\DashboardPage;

use Livewire\Component;

class PayableStatus extends Component
{
    public bool $isShow = false;
    public function onClickWid()
    {
        $this->isShow = $this->isShow ? false : true;
    }
    public function render()
    {
        return view('livewire.dashboard-page.payable-status');
    }
}
