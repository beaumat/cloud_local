<?php

namespace App\Livewire\Option;

use App\Services\SystemSettingServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Illuminate\Support\Str;

class OptionSettingsAccounting extends Component
{
    #[Reactive]
    public $systemSetting = [];
    public bool $SkipJournalEntry = false;
    public int $DateWarningDaysPast;
    public int $DateWarningDaysFuture;
    public string $ClosingDate;
    public string $SmallestCurrencyValue;

    public function mount()
    {
        $this->SkipJournalEntry = (bool) $this->returnArray('SkipJournalEntry');
        $this->DateWarningDaysPast = (int) $this->returnArray('DateWarningDaysPast');
        $this->DateWarningDaysFuture = (int) $this->returnArray('DateWarningDaysFuture');
        $this->ClosingDate = (string) $this->returnArray('ClosingDate');
        $this->SmallestCurrencyValue = (string) $this->returnArray('SmallestCurrencyValue');
    }
    public function returnArray($name): string
    {
        foreach ($this->systemSetting as $list) {
            if (Str::lower($list->NAME) == Str::lower($name)) {
                return $list->VALUE;
            }
        }
        dd("record not found : " . $name);
    }
    public function saveOn($name, $value, SystemSettingServices $systemSettingServices)
    {
        $systemSettingServices->SetValue($name, $value);
        session()->flash('message', $name . ' HAS BEEN SAVE!');
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
        return view('livewire.option.option-settings-accounting');
    }
}
