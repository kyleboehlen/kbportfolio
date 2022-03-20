@extends('admin.panel')

@section('contents')
    <object class="pdf-object" data="{{ Storage::url(config('filesystems.dir.documents') . 'resume.pdf') }}" type="application/pdf" width="100%" height="100%"> 
        <!-- Just use a modern browser ffs... -->
    </object>
@endsection