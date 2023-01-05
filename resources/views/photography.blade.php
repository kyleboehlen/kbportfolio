@extends('base')

@section('body')
    {{-- Logo --}}
    <a href="{{ route('index') }}">
        <img src="{{ Storage::url(config('filesystems.dir.images') . 'klogo.png') }}" id="logo"/>
    </a>
    
    <div class="photography-container d-flex flex-wrap align-content-start">
        {{-- Filters --}}
        <div id="filters-container" class="d-flex flex-column justify-content-center mb-3">
            <button type="button" class="btn btn-primary fs-5" id="photography-filter-btn" data-bs-toggle="collapse"
                data-bs-target="#filters-div" data-filters-changed="false">
                Filters <img src="{{ Storage::url(config('filesystems.dir.icons') . 'filter.png') }}"/>
            </button>
            <div class="collapse" id="filters-div">
                @foreach(config('photography.categories') as $id => $category)
                    <input type="checkbox" class="btn-check filter-btn-check" name="category-{{ $id }}" id="category-{{ $id }}" autocomplete="off"
                        data-filter-id="{{ $id }}" @if(in_array($id, $filter_categories)) checked @endif>
                    <label class="btn btn-outline-{{ $category['bg_color'] }} m-2 mt-3 mb-3" for="category-{{ $id }}">{{ $category['name'] }}</label>
                @endforeach
            </div>
        </div>

        {{-- Photos --}}
        @foreach($photos as $photo)
            <div class="photo-container d-flex justify-content-center" data-photo-id="{{ $photo->id }}"
                data-asset="{{ $photo->asset }}" data-shoot-id="{{ $photo->shoot_id }}" id="photo-container-{{ $photo->id }}">
                <img class="lazy" data-src="{{ Storage::url(config('filesystems.dir.photography.compressed') . $photo->asset) }}" />
            </div>
        @endforeach

        {{-- Copyright footer --}}
        <div class="photo-container">
            <p class="m-1">&copy; {{ \Carbon\Carbon::now()->format('Y') }} Kyle Boehlen</p>
        </div>
    </div>

    {{-- Full size viewer --}}
    <div id="full-size-viewer" class="flex-column justify-content-start"
        data-photo-ids="{{ json_encode($photos->pluck('id')->toArray()); }}" data-photo-id="0">
        <div id="viewer-toolbar" class="d-flex justify-content-center">
            <span id="viewer-left" class="badge rounded-pill bg-primary arrow-btns">
                <img src="{{ Storage::url(config('filesystems.dir.icons') . 'arrow-left.png') }}" />
            </span>

            @isset($shoot)
                <a href="/storage/images/photography/fullres/" id="download-link" class="btn btn-primary" target="_blank" download>
                    <span class="fs-4">Download</span>
                </a>
            @else
                <a href="{{ route('photography.shoot', ['shoot' => 0]) }}" id="viewer-shoot-link" class="btn btn-primary" target="_blank">
                    <span class="fs-4">View Shoot</span>
                </a>
            @endif

            <span id="viewer-right" class="badge rounded-pill bg-primary arrow-btns">
                <img src="{{ Storage::url(config('filesystems.dir.icons') . 'arrow-right.png') }}" />
            </span>

            <span id="viewer-close" class="badge rounded-pill bg-primary ms-auto">
                <img src="{{ Storage::url(config('filesystems.dir.icons') . 'close.png') }}" />
            </span>
        </div>

        <div id="full-res-container" class="d-flex justify-content-center align-content-center flex-grow-1">
            <img id="full-res-loader" class="full-res-img" src="{{ Storage::url(config('filesystems.dir.images') . 'loading.gif') }}" />
            
            @foreach($photos as $photo)
                <img id="full-res-img-{{ $photo->id }}" class="full-res-img" src="{{ Storage::url(config('filesystems.dir.photography.fullres')) }}" />
            @endforeach
        </div>
    </div>

    {{-- Background div --}}
    <div class="background" style="background-image: url({{ Storage::url(config('filesystems.dir.images') . 'photography-background-hi-res.jpg') }})">
        <!-- Hello there! -->
    </div
@endsection