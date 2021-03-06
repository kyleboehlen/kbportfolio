@extends('base')

@section('body')
    {{-- Flex container --}}
    <div class="contact-container">
        {{-- Logo --}}
        <a href="{{ route('index') }}">
            <img src="{{ Storage::url(config('filesystems.dir.images') . 'klogo.png') }}" id="logo"/>
        </a>
        
        {{-- Phone Number --}}
        <a class="contact-anchor" href="tel:{{ $user->phone_number }}">
            <p class="contact-text">{{ preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "$1.$2.$3", $user->phone_number) }}</p>
        </a>

        {{-- Email --}}
        <a class="contact-anchor" href="mailto:{{ $user->contact_email }}">
            <p class="contact-text">{{ $user->contact_email }}</p>
        </a>
    </div>

    {{-- Background div --}}
    <div class="background" style="background-image: url({{ Storage::url(config('filesystems.dir.images') . 'contact-background-hi-res.jpg') }})">
        <!-- Hello there! -->
    </div
@endsection