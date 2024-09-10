<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingslocation') }}"> Location : Custom Soa </a>
                    </h5>
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

                                        <th>Description</th>
                                        <th>Drug & Med</th>
                                        <th>Laboratory & Diagnois</th>
                                        <th>Operating Room Fee</th>
                                        <th>Supplies</th>
                                        <th>Admin & Other Fee</th>
                                        <th clss="col-1">Inactive</th>
                                        <th class="text-center col-1 bg-success">
                                            <a href="{{ route('maintenancesettingslocation_custom_soa_create', ['id' => $LOCATION_ID]) }}"
                                                class="text-white">
                                                <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>{{ $list->DESCRIPTION }}</td>
                                            <td class="text-right">{{ $list->DRUG_MED }}</td>
                                            <td class="text-right">{{ $list->LAB_DIAG }}</td>
                                            <td class="text-right">{{ $list->OPERATING_ROOM_FEE }}</td>
                                            <td class="text-right">{{ $list->SUPPLIES }}</td>
                                            <td class="text-right">{{ $list->ADMIN_OTHER_FEE }}</td>
                                            <td class="text-center">
                                                @if ($list->INACTIVE)
                                                    <span>Yes</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('maintenancesettingslocation_custom_soa_edit', ['id' => $LOCATION_ID, 'custom' => $list->ID]) }}"
                                                    class='btn btn-sm btn-info'>Edit</a>
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
