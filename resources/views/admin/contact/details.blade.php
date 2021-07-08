@extends('admin.panel')

@section('contents')
    {{-- Phone Number --}}
    <p class="fs-1 mt-5 mb-0"><u>Phone Number</u></p>
    <p class="fs-3 mt-0">{{ $user->phone_number }}</p>

    {{-- Email --}}
    <p class="fs-1 mt-5 mb-0"><u>Email</u></p>
    <p class="fs-3 mt-0">{{ $user->email }}</p>
@endsection