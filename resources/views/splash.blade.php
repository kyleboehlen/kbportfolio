@extends('base')

@section('body')
    {{-- Logo --}}
    <img class="context-logo" src="/storage/images/klogo.png" id="logo" />

    <div class="menu-container">

        {{-- Photography --}}
        <div class="menu-box" style="background-image: url(/storage/images/photography-background.jpg)">
                <div class="menu-anchor">
                    <p class="menu-text">Photography</p>
                </div>
            </a>
        </div>

        {{-- Software --}}
        <div class="menu-box" style="background-image: url(/storage/images/software-background.jpg)">
            <a class="menu-anchor" href="{{ route('software') }}">
                <div class="menu-anchor">
                    <p class="menu-text top">Software</p>
                </div>
            </a>
        </div>

        {{-- Resume --}}
        <div class="menu-box" style="background-image: url(/storage/images/resume-background.jpg)">
            <a class="menu-anchor" href="/storage/documents/resume.pdf" target="_blank">
                <div class="menu-anchor">
                    <p class="menu-text bottom">Resume</p>
                </div>
            </a>
        </div>

        {{-- Contact --}}
        <div class="menu-box" style="background-image: url(/storage/images/contact-background.jpg)">
            <a class="menu-anchor" href="{{ route('contact') }}">
                <div class="menu-anchor">
                    <p class="menu-text">Contact</p>
                </div>
            </a>
        </div>
    </div
@endsection