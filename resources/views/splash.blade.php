@extends('base')

@section('body')
    {{-- Desktop logo --}}
    <img src="/storage/klogo.png" id="desktop-logo"/>

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
                    <p class="menu-text">Software</p>
                </div>
            </a>
        </div>

        {{-- Resume --}}
        <div class="menu-box" style="background-image: url(/storage/resume-background.jpg)">
            <a class="menu-anchor" href="#">
                <div class="menu-anchor">
                    <p class="menu-text">Resume</p>
                </div>
            </a>
        </div>

        {{-- Contact --}}
        <div class="menu-box" style="background-image: url(/storage/contact-background.jpg)">
            <a class="menu-anchor" href="#">
                <div class="menu-anchor">
                    <p class="menu-text">Contact</p>
                </div>
            </a>
        </div>
    </div
@endsection