<?php

namespace App\Livewire\AccountJournal;

use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AccountJournalModal extends Component
{
    #[Reactive]
    public int $JOURNAL_NO;
    public bool $showModal = false;


    public function boot()
    {
        
    }


   
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.account-journal.account-journal-modal');
    }

}
