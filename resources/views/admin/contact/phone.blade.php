@extends('admin.panel')

@section('contents')
    {{-- Mobile form --}}
    <form class="row mt-5 d-flex flex-row justify-content-around" action="{{ route('admin.contact.update.phone-number') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Mobile input --}}
        <div class="d-block d-sm-none d-col-12">
            <input class="form-control form-control @error('phone-number') is-invalid @enderror" type="tel" name="phone-number" value="{{ old('phone-number') ?? $phone_number }}">
            @error('phone-number')
                <p class="text-danger fs-5">{{ $message }}</p>
            @enderror
        </div>
        <div class="d-block d-sm-none d-col-12">
            <button type="submit" class="btn btn-success mt-5 fs-5" style="width: 50%;">Update</button>
        </div>
    </form>

    {{-- Large form --}}
    <form class="row mt-5 d-flex flex-row justify-content-around" action="{{ route('admin.contact.update.phone-number') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Large input --}}
        <div class="d-none d-sm-block col-10">
            <input class="form-control form-control-lg @error('phone-number') is-invalid @enderror" type="tel" name="phone-number" value="{{ old('phone-number') ?? $phone_number }}">
        </div>
        <div class="d-none d-sm-block col-2">
            <button type="submit" class="btn btn-success fs-4" style="width: 100%;">Update</button>
        </div>
        <div class="d-none d-sm-block col-12">
            @error('phone-number')
                <p class="text-danger fs-4">{{ $message }}</p>
            @enderror
        </div>
    </form>
@endsection