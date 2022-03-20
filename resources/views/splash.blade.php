@extends('base')

@section('body')
    {{-- Logo --}}
    <img class="context-logo" src="{{ Storage::url(config('filesystems.dir.images') . 'klogo.png') }}" id="logo" />

    <div class="menu-container">

        {{-- Photography --}}
        <div class="menu-box" style="background-image: url({{ Storage::url(config('filesystems.dir.images') . 'photography-background.jpg') }})">
            <a class="menu-anchor" href="{{ route('photography') }}">
                <div class="menu-anchor">
                    <p class="menu-text">Photography</p>
                </div>
            </a>
        </div>

        {{-- Software --}}
        <div class="menu-box" style="background-image: url({{ Storage::url(config('filesystems.dir.images') . 'software-background.jpg') }})">
            <a class="menu-anchor" href="{{ route('software') }}">
                <div class="menu-anchor">
                    <p class="menu-text top">Software</p>
                </div>
            </a>
        </div>

        {{-- Resume --}}
        <div class="menu-box" style="background-image: url({{ Storage::url(config('filesystems.dir.images') . 'resume-background.jpg') }})">
            <a class="menu-anchor" href="{{ Storage::url(config('filesystems.dir.documents') . 'resume.pdf') }}" target="_blank">
                <div class="menu-anchor">
                    <p class="menu-text bottom">Resume</p>
                </div>
            </a>
        </div>

        {{-- Contact --}}
        <div class="menu-box" style="background-image: url({{ Storage::url(config('filesystems.dir.images') . 'contact-background.jpg') }})">
            <a class="menu-anchor" href="{{ route('contact') }}">
                <div class="menu-anchor">
                    <p class="menu-text">Contact</p>
                </div>
            </a>
        </div>
    </div
@endsection