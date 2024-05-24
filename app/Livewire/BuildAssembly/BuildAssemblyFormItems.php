<?php

namespace App\Livewire\BuildAssembly;

use App\Services\BuildAssemblyServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BuildAssemblyFormItems extends Component
{
    #[Reactive]
    public int $BUILD_ASSEMBLY_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $ASSEMBLY_ITEM_ID;

    private $buildAssemblyService;
    public function boot(BuildAssemblyServices $buildAssemblyServices)
    {
        $this->buildAssemblyService = $buildAssemblyServices;
    }
    public function render()
    {
        $dataList = $this->buildAssemblyService->ComponentList($this->BUILD_ASSEMBLY_ID);

        return view('livewire.build-assembly.build-assembly-form-items', ['dataList' => $dataList]);
    }
}
