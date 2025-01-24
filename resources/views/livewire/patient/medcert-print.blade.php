<div id="printableContent">
    {{-- Start Content --}}



    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    @if (empty($LOGO_FILE))
                        <img class="print-logo" style="position: fixed;left:25%"
                            src="{{ asset('dist/logo/vida_logo.png') }}" />
                        <div class="" style="position:fixed;left:30%;top:40px;">
                            <b>{{ $REPORT_HEADER_1 }}</b> <br />
                            <b>{{ $REPORT_HEADER_2 }}</b> <br />
                            <b>{{ $REPORT_HEADER_3 }}</b>
                        </div>
                    @else
                        {{-- nothing customize --}}
                        <img class="print-logo" src="{{ asset("dist/logo/$LOGO_FILE") }}" />
                    @endif

                </div>
                <div class="col-12">
                    <div class="row" style="position:fixed;left:30%; top:170px;">
                        <div class="col-12 mt-4">
                            <b class="h1">MEDICAL CERTIFICATE</b>
                        </div>

                    </div>
                </div>
                <div class="form-group" style="position:fixed;top:300px;left:100px;margin-right:130px;">
                    <div class="row">
                        <div class="col-12 text-left h4">
                            <b>January 6 2025</b>
                        </div>
                        <div class="col-12 text-left h4">
                            <b>To whom it may concern:</b>
                        </div>
                        <div class="col-12 text-left">
                            <b class="text-justify h4">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify
                                that patient MENDEZ, RYAN S., 47 years old, male, resident of Purok 3
                                Daisy St., Mintal, Davao City was diagnosed with End Stage Renal Disease secondary to
                                Diabetic Nephropathy and is enrolled/scheduled for Hemodialysis at Vida Dialysis Center,
                                trice a week, every Monday, Wednesday & Friday.
                            </b>
                        </div>

                        <div class="col-12 text-left">
                            <br /> <br /> <br /> <br />
                            <b class="text-justify h4">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is
                                issued to Mr. Mendez for whatever reason it may serve his purpose.
                            </b>
                        </div>

                        <div class="col-12 text-right">
                            <br /> <br /> <br /> <br />
                            <div class="center">
                                <b class="text-justify h4">
                                    LOUISE MARIE C. BASA, RN, MD


                                </b>
                                <br />Duty Physician
                                <br /> LIC No. 0167802

                            </div>

                            <br />

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>


































    {{-- End Content --}}
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
