<?php

namespace App\Livewire\User;

use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('User List')]
class UserList extends Component
{
    public $users = [];
    public $search = '';
    public function updatedsearch(UserServices $userServices)
    {
        $this->users = $userServices->Search($this->search);
    }
    public function delete($id, UserServices $userServices)
    {
        try {
            $userServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->users = $userServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $$errorMessage);
        }
    }
    public function mount(UserServices $userServices)
    {
        $this->users = $userServices->Search($this->search);
    }
    public function render()
    {
        return view('livewire.user.user-list');
    }
}
