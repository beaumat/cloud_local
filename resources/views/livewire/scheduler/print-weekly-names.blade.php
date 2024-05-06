<div class="row">
    @foreach ($contactList as $list)
        <div
            class="col-12 text-left top-line2 @if ($list['TYPE'] == 2) bgYellow @endif @if ($list['TYPE'] == 3) bgOrange @endif ">
            {{ $list['ID'] }} {{ $list['NAME'] }}
        </div>
    @endforeach
</div>
