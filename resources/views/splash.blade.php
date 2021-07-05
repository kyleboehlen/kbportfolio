@extends('base')

@section('body')
    {{-- Logo --}}
    <img class="context-logo" src="/storage/klogo.png" id="logo" />

    <div class="menu-container">

        {{-- Photography --}}
        <div class="menu-box" style="background-image: url(/storage/photography-background.jpg)">
            <a class="menu-anchor" href="#">
                <div class="menu-anchor">
                    <p class="menu-text">Photography</p>
                </div>
            </a>
        </div>

        {{-- Software --}}
        <div class="menu-box" style="background-image: url(/storage/software-background.jpg)">
            <a class="menu-anchor" href="#">
                <div class="menu-anchor">
                    <p class="menu-text top">Software</p>
                </div>
            </a>
        </div>

        {{-- Resume --}}
        <div class="menu-box" style="background-image: url(/storage/resume-background.jpg)">
            <a class="menu-anchor" href="/storage/documents/resume.pdf" target="_blank">
                <div class="menu-anchor">
                    <p class="menu-text bottom">Resume</p>
                </div>
            </a>
        </div>

        {{-- Contact --}}
        <div class="menu-box" style="background-image: url(/storage/contact-background.jpg)">
            <a class="menu-anchor" href="{{ route('contact') }}">
                <div class="menu-anchor">
                    <p class="menu-text">Contact</p>
                </div>
            </a>
        </div>
    </div
@endsection