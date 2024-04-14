<div class="row">
    <div class="col-md-4">
        <table class="table table-sm text-xs table-bordered ">
            <thead>
                <tr class="text-center bg-sky text-white">
                    <th class="col-2">No.</th>
                    <th class="col=10">DATE OF TREATMENT</th>
                </tr>
            </thead>
            <tbody class="text-dark text-lg text-center">
                @foreach ($hemoList as $list)
                    @php
                        $i++;
                    @endphp
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
