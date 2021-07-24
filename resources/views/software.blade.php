@extends('base')

@section('body')
    {{-- Flex container --}}
    <div class="software-container d-flex flex-column justify-content-evenly align-items-center">
        {{-- Logo --}}
        <a href="{{ route('index') }}">
            <img src="/storage/images/klogo.png" id="logo"/>
        </a>
    
        @foreach($projects as $project)
            <div class="project d-flex justify-content-evenly">
                <div class="logo-container" style="background-image: url(/storage/images/software/{{ $project->logo }});"></div>

                <div class="details-container">
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
                                Private Codebase&nbsp;&nbsp;&nbsp;<img class="btn-img" src="{{ asset('storage/icons/private.png') }}" />
                            </button>
                        @else
                            <a class="btn btn-primary fs-5" href="{{ $project->app_link }}" target="_blank">
                                View Codebase&nbsp;&nbsp;&nbsp;<img class="btn-img" src="{{ asset('storage/icons/code.png') }}" />
                            </a>
                        @endif

                        <a class="btn btn-primary fs-5" href="{{ $project->app_link }}" target="_blank">
                            View App&nbsp;&nbsp;&nbsp;<img class="btn-img" src="{{ asset('storage/icons/external.png') }}" />
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Background div --}}
    <div class="background" style="background-image: url(/storage/images/software-background-hi-res.jpg)">
        <!-- Hello there! -->
    </div
@endsection