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

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ $route == 'admin' ? 'active' : '' }}" href="{{ route('admin') }}">Home</a>
                    </li>

                    <span class="navbar-text"> | </span>

                    <li class="nav-item">
                        <a class="nav-link {{ strpos($route, 'admin.photography') !== false ? 'active' : '' }}" href="{{ route('admin.photography') }}">Photography</a>
                    </li>

                    <span class="navbar-text"> | </span>

                    <li class="nav-item">
                        <a class="nav-link {{ strpos($route, 'admin.software') !== false ? 'active' : '' }}" href="{{ route('admin.software') }}">Software</a>
                    </li>

                    <span class="navbar-text"> | </span>

                    <li class="nav-item">
                        <a class="nav-link {{ strpos($route, 'admin.resume') !== false ? 'active' : '' }}" href="{{ route('admin.resume') }}">Resume</a>
                    </li>

                    <span class="navbar-text"> | </span>

                    <li class="nav-item">
                        <a class="nav-link {{ strpos($route, 'admin.contact') !== false ? 'active' : '' }}" href="{{ route('admin.contact') }}">Contact</a>
                    </li>
                </ul>
            </div>

            <div class="float-end auth-div">
                @if(\Auth::check())
                    <button class="btn btn-outline-success auth-hover-hide" type="button">Admin</button>
                    <form class="auth-hover-show" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger" type="submit">Log Out</button>
                    </form>
                @else
                    <button class="btn btn-outline-danger auth-hover-hide" type="button">Guest</button>
                    <a class="btn btn-success auth-hover-show" href="{{ route('login') }}">Login</a>
                @endif
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