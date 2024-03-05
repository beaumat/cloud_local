<table class="text-xs">
    <thead class="text-xs">
        <tr class="bg-info">
            <th>S</th>
            <th>W</th>
            <th>P</th>
            <th>A</th>
            <th>C</th>
        </tr>
    </thead>
    <tbody class="text-xs">
        @foreach ($totalPatientsByShift as $list)
            <tr>
                <td class="text-primary">{{ $list->SHIFT_ID }}</td>
                <td class="text-primary">{{ $list->W }}</td>
                <td class="text-primary">{{ $list->P }}</td>
                <td class="text-primary">{{ $list->A }}</td>
                <td class="text-primary">{{ $list->C }}</td>
            </tr>
        @endforeach
    </tbody>

</table>