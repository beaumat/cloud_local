    <div class="row">
        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
        <div class="col-10">
            <div class="row">

                <div class="col-11">
                    @if ($FILE_PATH)
                        <a href='{{ $FILE_PATH }}' target="_blank" class="btn btn-primary btn-xs w-25"> Show Docs</a>
                        @if (!$FILE_CONFIRM_DATE)
                            <label class="text-danger text-sm"> File not confirm by main office</label>
                        @endif
                    @else
                        <div class="input-group input-group-sm">
                            <div class="custom-file text-xs">
                                <input type="file" class="custom-file-input text-xs" id="fileUpload"
                                    wire:model='PDF'>
                                <label class="custom-file-label text-xs" for="fileUpload">
                                    @if ($PDF)
                                        {{ $PDF->getClientOriginalName() }}
                                    @else
                                        Choose file
                                    @endif
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-1">
                    @if ($PDF)
                        <i class="fa fa-check-circle text-success fa-2x" aria-hidden="true"></i>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-2">

            @if (!$FILE_CONFIRM_DATE)
                @if (!$FILE_PATH)
                    <button type="button" class="btn btn-xs btn-success w-100"
                        wire:confirm='Are you sure to upload this document?' wire:click='uploading()'>Upload</button>
                @else
                    <button type="button" class="btn btn-xs btn-danger w-100"
                        wire:confirm='Are you sure to remove this document?' wire:click='removeFile()'>Remove</button>
                @endif

            @endif
        </div>
    </div>
