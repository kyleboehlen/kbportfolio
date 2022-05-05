@extends('base')

@section('body')
    {{-- Session first time alert --}}
    @isset($admin_alert)
        <script>
            swal.fire({
                title: '{{ $admin_alert['title'] }}',
                icon: '{{ $admin_alert['icon'] }}',
                iconColor: '#38c172',
                html: `<p class="alert-text">{{ $admin_alert['body'] }}</p>`,
                padding: '.5rem',
                showCancelButton: false,
                confirmButtonColor: '#38c172',
                confirmButtonText: 'Okay',
                background: '#2e3535',
            });
        </script>
    @endisset

    {{-- Success alert --}}
    @if(!is_null(session('success_alert')))
        <script>
            swal.fire({
                title: 'Success!',
                icon: 'success',
                iconColor: '#38c172',
                html: `<p class="alert-text">{{ session('success_alert') }}</p>`,
                padding: '.5rem',
                showCancelButton: false,
                confirmButtonColor: '#38c172',
                confirmButtonText: 'Okay',
                background: '#2e3535',
            });
        </script>
    @endif

    {{-- Failure alert --}}
    @if(!is_null(session('failure_alert')))
        <script>
            swal.fire({
                title: 'Failure',
                icon: 'error',
                iconColor: '#e3342f',
                html: `<p class="alert-text">{{ session('failure_alert') }}</p>`,
                padding: '.5rem',
                showCancelButton: false,
                confirmButtonColor: '#38c172',
                confirmButtonText: 'Okay',
                background: '#2e3535',
            });
        </script>
    @endif

    {{-- Background --}}
    <div class="background" style="background-image: url({{ Storage::url(config('filesystems.dir.images') . 'admin-background.jpg') }})"></div>

    {{-- Top Nav --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark background-z-index">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('index') }}">
                <img src="{{ Storage::url(config('filesystems.dir.images') . 'kinglogo.png') }}" id="logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="float-end auth-div d-block d-lg-none d-xl-none d-xxl-none">
                @include('admin.includes.auth-btn')
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 text-center">
                    <li class="nav-item">
                        <a class="nav-link fs-2 {{ $route == 'admin' ? 'active' : '' }}" href="{{ route('admin') }}">Home</a>
                    </li>

                    <span class="navbar-text fs-2 d-none d-lg-block d-xl-block d-xxl-block"> | </span>

                    <li class="nav-item">
                        <a class="nav-link fs-2 {{ strpos($route, 'admin.photography') !== false ? 'active' : '' }}" href="{{ route('admin.photography') }}">Photography</a>
                    </li>

                    <span class="navbar-text fs-2 d-none d-lg-block d-xl-block d-xxl-block"> | </span>

                    <li class="nav-item">
                        <a class="nav-link fs-2 {{ strpos($route, 'admin.software') !== false ? 'active' : '' }}" href="{{ route('admin.software') }}">Software</a>
                    </li>

                    <span class="navbar-text fs-2 d-none d-lg-block d-xl-block d-xxl-block"> | </span>

                    <li class="nav-item">
                        <a class="nav-link fs-2 {{ strpos($route, 'admin.resume') !== false ? 'active' : '' }}" href="{{ route('admin.resume') }}">Resume</a>
                    </li>

                    <span class="navbar-text fs-2 d-none d-lg-block d-xl-block d-xxl-block"> | </span>

                    <li class="nav-item">
                        <a class="nav-link fs-2 {{ strpos($route, 'admin.contact') !== false ? 'active' : '' }}" href="{{ route('admin.contact') }}">Contact</a>
                    </li>

                    <span class="navbar-text fs-2 d-none d-lg-block d-xl-block d-xxl-block"> | </span>

                    <li class="nav-item">
                        <a class="nav-link fs-2 {{ strpos($route, 'admin.bots') !== false ? 'active' : '' }}" href="{{ route('admin.bots') }}">Bots</a>
                    </li>
                </ul>
            </div>

            <div class="float-end auth-div d-none d-lg-block d-xl-block d-xxl-block">
                @include('admin.includes.auth-btn')
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <div class="container-fluid background-z-index">
        <div class="row d-block d-md-none mt-3">
            {{-- Top nav --}}
            <div class="col-12">
                <div class="top-nav {{ isset($action_nav_opts) ? '' : 'outline' }}">
                    @isset($action_nav_opts)
                        <div class="panel card bg-dark text-white">
                            <div class="card-header fs-2 text-center"
                                    data-bs-toggle="collapse"
                                    data-bs-target=".mobile-action-collapse"
                                    aria-expanded="false"
                                    aria-controls="action-collapse">
                                Actions
                            </div>
                            <div class="card-body action-nav collapse mobile-action-collapse">
                                @include('admin.includes.action-nav')  
                            </div>
                        </div>
                    @endisset 
                </div>
            </div>
        </div>

        <div class="row justify-content-evenly">
            {{-- Side nav --}}
            <div class="col-lg-3 col-md-4 d-none d-md-block mt-4" style="max-width: 350px;">
                <div class="side-nav {{ isset($action_nav_opts) ? '' : 'outline' }}">
                    @isset($action_nav_opts)
                        <div class="panel full-height card bg-dark text-white">
                            <div class="card-header fs-1 text-center">
                                Actions
                            </div>
                            <div class="card-body action-nav">
                                @include('admin.includes.action-nav') 
                            </div>
                        </div>
                    @else
                        <div class="blockrain d-none d-lg-block" style="margin: auto; margin-top: 4%; height: 96%; width: 97%;">
                        </div>
                
                        <script>
                            $('.blockrain').blockrain();
                        </script>

                        @push('head')
                            <link rel="stylesheet" href="{{ asset('blockrain/blockrain.css') }}">
                            <script src="{{ asset('blockrain/blockrain.jquery.min.js') }}"></script>
                        @endpush
                    @endisset
                </div>
            </div>

            {{-- Panel --}}
            <div class="col-xl-7 col-md-8 col-sm-12 mt-4 collapse show mobile-action-collapse">
                @yield('panel')
            </div>
        </div>
    </div>

    {{-- Copywrite --}}
    <div class="container-fluid background-z-index">
        <div class="row justify-content-center">
            <div class="col-md-4 copywrite">
                <p class="mt-4">&copy; {{ \Carbon\Carbon::now()->format('Y') }} Kyle Boehlen</p>
            </div>
        </div>
    </div>
@endsection