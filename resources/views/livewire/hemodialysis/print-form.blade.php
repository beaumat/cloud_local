<div class="content-wrapper" id="printableContent">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>

    @foreach ($HEMO_ID as $ID)
        @livewire('Hemodialysis.PrintContent', ['HEMO_ID' => $ID])
        <div class="page-break"></div>
    @endforeach

</div>

@script
    <script>
  

        function printPageAndClose() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 100);
        }

        window.addEventListener('beforeprint', function() {
            printPageAndClose();
        });
    </script>
@endscript
