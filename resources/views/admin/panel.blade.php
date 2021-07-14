@extends('admin.base')

@section('panel')
    <div class="panel full-height card bg-dark text-white scroll">
        <div class="card-header fs-1 text-center">
            {{ $card_header }}
        </div>
        <div class="card-body text-center">
            @yield('contents')
        </div>
    </div>
@endsection