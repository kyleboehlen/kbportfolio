@extends('base')

@section('body')
    {{-- Session first time alert --}}
    @isset($admin_alert)
        <script>
            swal.fire({
                title: '{{ $admin_alert['title'] }}',
                icon: '{{ $admin_alert['body'] }}',
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

    {{-- Background --}}
    <div class="background" style="background-image: url(/storage/admin-background.jpg)"></div>

    {{-- Top Nav --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('index') }}">
                <img src="/storage/klogo.png" id="logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="float-end auth-div d-block d-lg-none d-xl-none d-xxl-none">
                @include('admin.includes.auth-btn')
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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
                </ul>
            </div>

            <div class="float-end auth-div d-none d-lg-block d-xl-block d-xxl-block">
                @include('admin.includes.auth-btn')
            </div>
        </div>
    </nav>

      <div class="spacer"></div>

      <div class="container-fluid">
        <div class="row justify-content-center">
            {{-- Side nav --}}
            <div class="col-md-2">
                <div class="side-nav {{ isset($side_nav_opts) ? '' : 'outline' }}">

                </div>
            </div>

            {{-- Spacer --}}
            <div class="col-md-1"></div>

            {{-- Panel --}}
            <div class="col-md-7">
                @yield('panel')
            </div>
        </div>
      </div>

      <div class="container-fluid">
          <div class="row justify-content-center">
              <div class="col-md-4 copywrite">
                  <br/>
                  <p class="mt-2">&copy; {{ \Carbon\Carbon::now()->format('Y') }} Kyle Boehlen</p>
              </div>
          </div>
      </div>
@endsection