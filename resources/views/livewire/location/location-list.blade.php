<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingslocation') }}"> Location </a></h5>
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

                                        <th>Name</th>
                                        <th clss="col-1">Inactive</th>
                                        <th class="text-center col-1">
                                            <a href="{{ route('maintenancesettingslocation_create') }}"
                                                class="text-white">
                                                <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($locations as $list)
                                        <tr>
                                            <td> {{ $list->NAME }}</td>
                                            <td>
                                                @if ($list->INACTIVE)
                                                    <strong class="text-danger">Yes</strong>
                                                @else
                                                    <strong class="text-primary">No</strong>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('maintenancesettingslocation_edit', ['id' => $list->ID]) }}"
                                                    class="btn btn-info btn-xs">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                </a>

                                                <a href="{{ route('maintenancesettingslocation_doctor', ['id' => $list->ID]) }}"
                                                    class="btn btn-success btn-xs">
                                                    <i class="fas fa-user" aria-hidden="true"></i>
                                                </a>


                                                <button type="button" wire:click='delete({{ $list->ID }})'
                                                    wire:confirm="Are you sure you want to delete this?"
                                                    class="btn btn-danger btn-xs">
                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                    </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
