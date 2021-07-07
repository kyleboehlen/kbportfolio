@extends('admin.base')

@section('panel')
    <div class="panel full-height card bg-dark text-white">
        <div class="card-header fs-1 text-center">
            {{ $card_header }}
        </div>
        <div class="card-body text-center">
            <object class="pdf-object" data="/storage/documents/resume.pdf" type="application/pdf" width="100%" height="100%"> 
                <!-- Just use a modern browser ffs... -->
            </object>
        </div>
    </div>
@endsection