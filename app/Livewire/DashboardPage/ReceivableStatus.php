<?php

namespace App\Livewire\DashboardPage;

use Livewire\Component;

class ReceivableStatus extends Component
{

    public bool $isShow = false;
    public function onClickWid()
    {
        $this->isShow = $this->isShow ? false : true;
    }
    public function render()
    {
        return view('livewire.dashboard-page.receivable-status');
    }
}
