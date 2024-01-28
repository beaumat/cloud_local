<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingsroles') }}"> Roles & Permission </a></h5>
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
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">

                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-sm bg-sky">
                                    <tr>
                                        <th>Roles</th>
                                        <th class="col-1">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach ($roles as $list)
                                        <tr>
                                            <td> {{ $list->name }}</td>
                                            <td>
                                                <a href="{{ route('maintenancesettingsroles_permission', ['id' => $list->id]) }}"
                                                    class="btn btn-sm btn-info w-100">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i>
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
