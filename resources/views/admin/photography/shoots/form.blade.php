@extends('admin.panel')

@section('contents')
    @isset($shoot)
        <div class="row justify-content-center mt-1 mb-4">
            <div class="col-12 col-md-4">
                <a href="{{ route('admin.photography.photos.upload', ['shoot' => $shoot->id]) }}" class="btn btn-primary fs-2">
                    <img src="/storage/icons/upload.png" /> &nbsp;Upload Photos
                </a>
            </div>
        </div>
    @endisset

    <div class="row justify-content-center mt-4">
        <div class="col-12 col-md-8">
            <form action="{{ isset($shoot) ? route('admin.photography.shoot.update', ['shoot' => $shoot->id]) : route('admin.photography.shoot.store') }}"
                class="mb-6" method="POST">
                @csrf

                {{-- Name --}}
                <div class="mb-3">
                    <input type="text" class="form-control-lg" name="name" placeholder="Shoot Name" style="width: 100%;" required
                        value="{{ old('name') ?? (isset($shoot) ? $shoot->name : '') }}"/>
                    @error('name')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date --}}
                <div class="mb-3">
                    <input type="date" class="form-control-lg" name="date" style="width: 100%;"
                        value="{{ old('date') ?? (isset($shoot) ? $shoot->shot_on : '') }}"/>
                    @error('name')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Default Categories --}}
                <div class="mb-3">
                    @foreach(config('photography.categories') as $id => $category)
                        <input type="checkbox" class="btn-check" name="category-{{ $id }}" id="category-{{ $id }}" autocomplete="off"
                            @if(!is_null(old('category-' . $id)) || (isset($shoot) && in_array($id, $shoot->categories))) checked @endif>
                        <label class="btn btn-outline-{{ $category['bg_color'] }} m-1" for="category-{{ $id }}">{{ $category['name'] }}</label>
                    @endforeach
                </div>

                {{-- Desc --}}
                <div class="mb-3">
                    <textarea class="form-control" name="desc"
                        placeholder="Shoot Description">{{ old('desc') ?? (isset($shoot) ? $shoot->desc : '') }}</textarea>
                    @error('desc')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-success mt-3" style="width: 100%;">
                    @isset($shoot) Update @else Create @endisset
                </button>
            </form>

            {{-- Delete --}}
            @isset($shoot)
                <form id="delete-shoot-form" action="{{ route('admin.photography.shoot.destroy', ['shoot' => $shoot->id]) }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-danger mt-5" style="width: 100%;"
                        onclick="event.preventDefault(); verifyDeleteForm('Are you sure you want to delete this shoot?', '#delete-shoot-form')">
                        Delete
                    </button>
                </form>
            @endisset
        </div>
    </div>
@endsection