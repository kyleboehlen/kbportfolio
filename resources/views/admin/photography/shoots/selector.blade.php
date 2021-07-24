@extends('admin.panel')

@section('contents')
    @if($shoots->count() > 0)
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <select class="form-select form-select-lg mt-5" id="shoot-selector">
                    <option disabled selected>Select a shoot to edit</option>
                    @foreach($shoots as $shoot)
                        <option value="{{ $shoot->id }}">{{ $shoot->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <img src="/storage/icons/no-camera.png" class="rounded mx-auto d-block mt-5 mb-3" />
        <h2 class="display-5 text-center mt-3">No shoots found.</h2>
    @endif
@endsection