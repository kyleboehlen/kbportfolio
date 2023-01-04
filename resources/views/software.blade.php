@extends('base')

@section('body')
    {{-- Logo --}}
    <a href="{{ route('index') }}">
        <img src="{{ Storage::url(config('filesystems.dir.images') . 'klogo.png') }}" id="logo"/>
    </a>

    {{-- Flex container --}}
    <div class="software-container d-flex flex-column justify-content-evenly align-items-center mt-5 pt-5">
        <div class="mt-5 mb-5 pt-5 pb-5"></div>
        <div class="mt-5 mb-5 pt-5 pb-5"></div>
        @foreach($projects as $project)
            <div class="project d-flex justify-content-evenly mb-5">
                <div class="logo-container" style="background-image: url({{ Storage::url(config('filesystems.dir.software') . $project->logo) }});"></div>

                <div class="details-container flex-grow-1">
                    @foreach($project->technologies as $technology)
                        <span class="badge bg-{{ $technologies[$technology]['bg_color'] }} {{ $technologies[$technology]['text_dark'] ? 'text-dark' : '' }}">
                            {{ $technologies[$technology]['name'] }}
                        </span>
                    @endforeach

                    <p class="mt-3 mb-0">{{ ucwords($project->type) }}</p>
                    <h2 class="mt-0 mb-0">{{ $project->name }}</h2>
                    <p class="mb-3 mt-0">{{ $project->desc }}</p>

                    <div class="button-container d-flex justify-content-between">
                        @if(is_null($project->codebase_link))
                            <button class="btn btn-primary fs-5" href="{{ $project->app_link }}" disabled>
                                Private Codebase&nbsp;&nbsp;&nbsp;<img class="btn-img" src="{{ Storage::url(config('filesystems.dir.icons') . 'private.png') }}" />
                            </button>
                        @else
                            <a class="btn btn-primary fs-5" href="{{ $project->codebase_link }}" target="_blank">
                                View Codebase&nbsp;&nbsp;&nbsp;<img class="btn-img" src="{{ Storage::url(config('filesystems.dir.icons') . 'code.png') }}" />
                            </a>
                        @endif

                        <a class="btn btn-primary fs-5" href="{{ $project->app_link }}" target="_blank">
                            View App&nbsp;&nbsp;&nbsp;<img class="btn-img" src="{{ Storage::url(config('filesystems.dir.icons') . 'external.png') }}" />
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Background div --}}
    <div class="background" style="background-image: url({{ Storage::url(config('filesystems.dir.images') . 'software-background-hi-res.jpg') }})">
        <!-- Hello there! -->
    </div
@endsection
