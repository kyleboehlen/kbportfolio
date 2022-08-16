@extends('admin.panel')

@section('contents')

    @if($shoots->count() > 0)
        {{-- Fileinput requirements --}}
        @push('head')
            <!-- default icons used in the plugin are from Bootstrap 5.x icon library -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">

            <!-- the fileinput plugin styling CSS file -->
            <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.2/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />

            <!-- the main fileinput plugin script JS file -->
            <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.2/js/fileinput.min.js"></script>
        @endpush

        {{-- File input script --}}
        <script>
            $(document).ready(function() {
                $("#upload-photos-input").fileinput({
                    uploadUrl: "{{ route('admin.photography.photo.store', ['shoot' => $shoot_id ?? 0]) }}",
                    uploadExtraData: function(previewId, index) {
                        return {
                            _token: "{{ csrf_token() }}",
                        };
                    },
                    initialPreviewAsData: true,
                    fileActionSettings : {
                        showRemove: true,
                        showUpload: false,
                        showZoom: false,
                        showDrag: false
                    },
                    allowedFileExtensions: ['png', 'jpg', 'jpeg'],
                    maxFileSize: 30000,
                    maxFileCount: 40,
                    showUpload: true,
                    showRemove: true,
                    msgUploadEnd: 'Success! Images uploaded.'
                });
                // .on('filebatchuploadcomplete', function(event, data){
                //     alert('all files uploaded');
                // });
                
                $('#upload-photos-selector').change(function(e){
                    e.preventDefault();
        
                    var base_url = "{{ route('admin.photography.photo.store', ['shoot' => $shoot_id ?? 0]) }}";
                    var shoot_id = $(this).find(':selected').val();
                    var new_url = base_url.substring(0, (base_url.lastIndexOf('/') + 1)) + shoot_id;
        
                    $("#upload-photos-input").fileinput('refresh', {
                        uploadUrl: new_url,
                    });
        
                    $('#upload-photos-container').show();
                });
            });
        </script>

        {{-- Shoot select --}}
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <select class="form-select form-select-lg mt-5 mb-3" id="upload-photos-selector">
                    @if(is_null($shoot_id))
                        <option disabled selected>Select a shoot to upload photos</option>
                    @endif

                    @foreach($shoots as $shoot)
                        <option value="{{ $shoot->id }}" @if(!is_null($shoot_id) && $shoot_id == $shoot->id) selected @endif>
                            {{ $shoot->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- File input --}}
        <div class="row mt-3" id="upload-photos-container" @if(is_null($shoot_id)) style="display: none;" @endif>
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="file-loading">
                        <input id="upload-photos-input" type="file" name="file" accept="image/jpeg,image/jpg,image/png"
                            data-min-file-count="1" data-browse-on-zone-click="true" multiple>
                    </div>
                </div>
            </div>
        </div>
    @else
        @include('admin.includes.no-shoots-msg')
    @endif
@endsection
