<div class="row">
    <div class="col-md-12">
        <table class="table table-sm table-bordered table-hover">
            <thead class="text-xs">
                <tr class="text-center bg-sky text-white">
                    <th class="col-1 text-center">No.</th>
                    <th class="col-1 text-center">Reference</th>
                    <th class="col-1 text-center">Date</th>
                    <th class="">Doctor Order</th>
                    <th class="col-1 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="text-dark text-xs">
                @foreach ($hemoList as $list)
                    @php
                        $i++;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i }}</td>
                        <td class="text-center">
                            <a target="_BLANK"
                                href="{{ route('patientshemo_edit', ['id' => $list->ID]) }}">{{ $list->CODE }}</a>
                        </td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</td>
                        <td>{{ $list->DOCTOR_ORDER }}</td>
                        <td class="text-center">
                            <button class="btn btn-xs btn-primary" wire:click='OpenModify({{ $list->ID }})'>
                                <i class="fa fa-wrench" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    @livewire('PhilHealth.DoctorOrder')
</div>
