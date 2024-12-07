<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingslocation') }}"> Soa Item </a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" wire:model.live.debounce.150ms='search'
                                        class="w-100 form-control form-control-sm" placeholder="Search" />
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>

                                        <th class="col-2">Type</th>
                                        <th class="col-4">Item </th>
                                        <th class="col-2">Unit </th>
                                        <th class="col-2">Rate </th>
                                        <th class="col-1 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>
                                                @if ($list->ID == $editid)
                                                    <select name="editTYPE{{ $list->ID }}"
                                                        class="form-control form-control-sm" wire:model='editTYPE'>
                                                        @foreach ($typeList as $dataList)
                                                            <option value="{{ $dataList->ID }}">
                                                                {{ $dataList->DESCRIPTION }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    {{ $list->TYPE_NAME }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($list->ID == $editid)
                                                    <input name="editITEM_NAME{{ $list->ID }}" type="text"
                                                        class="form-control form-control-sm"
                                                        wire:model='editITEM_NAME' />
                                                @else
                                                    {{ $list->ITEM_NAME }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($list->ID == $editid)
                                                    <input name="editUNIT_NAME" type="text"
                                                        class="form-control form-control-sm"
                                                        wire:model='editUNIT_NAME' />
                                                @else
                                                    {{ $list->UNIT_NAME }}
                                                @endif
                                            </td>

                                            <td class="text-right">
                                                @if ($list->ID == $editid)
                                                    <input name="editRATE" type="number"
                                                        class="form-control form-control-sm" wire:model='editRATE' />
                                                @else
                                                    {{ number_format($list->RATE, 2) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($editid === $list->ID)
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        wire:click='Update()'>Update</button>
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        wire:click='Canceled()'>Cancel</button>
                                                @else
                                                    <button type="button" class='btn btn-sm btn-primary'
                                                        wire:click='Edit({{ $list->ID }})'>Edit</button>
                                                    <button type="button" class='btn btn-sm btn-danger'>Delete</button>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <form id="quickForm" wire:submit.prevent='Add'>
                                            <td>
                                                <select class="form-control form-control-sm" wire:model='TYPE'>
                                                    <option value="0"></option>
                                                    @foreach ($typeList as $list)
                                                        <option value="{{ $list->ID }}">
                                                            {{ $list->DESCRIPTION }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    wire:model='ITEM_NAME' />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    wire:model='UNIT_NAME' />
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm"
                                                    wire:model='RATE' />
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-success w-100">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
