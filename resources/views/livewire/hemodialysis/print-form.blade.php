<div class="content-wrapper" id="printableContent">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    @livewire('Hemodialysis.PrintContent')

</div>

@script
    <script>
        $wire.on('print', () => {
            var printContents = document.getElementById('printableContent').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        });
    </script>
@endscript
