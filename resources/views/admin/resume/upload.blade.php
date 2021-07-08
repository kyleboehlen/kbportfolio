@extends('admin.panel')

@section('contents')
    {{-- Mobile form --}}
    <form class="row mt-5 d-flex flex-row justify-content-around" action="{{ route('admin.resume.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Mobile input --}}
        <div class="d-block d-sm-none d-col-12">
            <input class="form-control form-control @error('resume') is-invalid @enderror" type="file" name="resume" accept="application/pdf">
            @error('resume')
                <p class="text-danger fs-5">{{ $message }}</p>
            @enderror
        </div>
        <div class="d-block d-sm-none d-col-12">
            <button type="submit" class="btn btn-success mt-5 fs-5" style="width: 50%;">Upload</button>
        </div>
    </form>

    {{-- Large form --}}
    <form class="row mt-5 d-flex flex-row justify-content-around" action="{{ route('admin.resume.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Large input --}}
        <div class="d-none d-sm-block col-10">
            <input class="form-control form-control-lg @error('resume') is-invalid @enderror" type="file" name="resume" accept="application/pdf">
        </div>
        <div class="d-none d-sm-block col-2">
            <button type="submit" class="btn btn-success fs-4" style="width: 100%;">Upload</button>
        </div>
        <div class="d-none d-sm-block col-12">
            @error('resume')
                <p class="text-danger fs-4">{{ $message }}</p>
            @enderror
        </div>
    </form>
@endsection