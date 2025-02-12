<div class="content-wrapper" id="printableContent">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>

    @livewire('HemoAgreementForm.Page1', ['HEMO_ID' => $HEMO_ID])
    <div class="page-break"></div>
    @livewire('HemoAgreementForm.Page2', ['HEMO_ID' => $HEMO_ID])

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
