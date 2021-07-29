@extends('base')

@section('body')
    {{-- Logo --}}
    <a href="{{ route('index') }}">
        <img src="/storage/images/klogo.png" id="logo"/>
    </a>
    
    <div class="photography-container d-flex flex-wrap align-content-start">
        {{-- Filters --}}
        <div id="filters-container" class="d-flex flex-column justify-content-center mb-3">
            <button type="button" class="btn btn-primary fs-5" id="photography-filter-btn" data-bs-toggle="collapse"
                data-bs-target="#filters-div" data-filters-changed="false">
                Filters <img src="/storage/icons/filter.png"/>
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
            <div class="photo-container d-flex justify-content-center">
                <img src="/storage/images/photography/compressed/{{ $photo->asset }}" />
            </div>
        @endforeach
        <div class="photo-container">
            <p class="m-1">&copy; {{ \Carbon\Carbon::now()->format('Y') }} Kyle Boehlen</p>
        </div>
    </div>

    {{-- Background div --}}
    <div class="background" style="background-image: url(/storage/images/photography-background-hi-res.jpg)">
        <!-- Hello there! -->
    </div
@endsection