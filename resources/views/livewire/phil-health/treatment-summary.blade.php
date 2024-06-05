<div class="row">
    <div class="col-md-4">
        <table class="table table-sm table-bordered table-hover">
            <thead class="text-xs">
                <tr class="text-center bg-sky text-white">
                    <th class="col-2 text-center">No.</th>
                    <th class="col-2">Reference</th>
                    <th class="col=8 text-center">Date</th>

                </tr>
            </thead>
            <tbody class="text-dark text-xs">
                @foreach ($hemoList as $list)
                    @php
                        $i++;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i }}</td>
                        <td>
                            <a target="_BLANK"
                                href="{{ route('patientshemo_edit', ['id' => $list->ID]) }}">{{ $list->CODE }}</a>
                        </td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
