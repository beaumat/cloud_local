<?php

namespace App\Livewire\DashboardPage;

use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{

    public function mount()
    {
        if (auth()->user()->can('direct-to-treatment')) {
            if (auth()->user()->can('patient.treatment.view')) {
                return Redirect::route('patientshemo');
            }
        }
    }
    public function render()
    {
        return view('livewire.dashboard-page.dashboard');
    }
}
