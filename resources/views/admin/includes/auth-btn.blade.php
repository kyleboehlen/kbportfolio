@if(\Auth::check())
    <button class="btn btn-outline-success fs-3 auth-hover-hide" type="button">Admin</button>
    <form class="auth-hover-show" action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-danger fs-3" type="submit">Log Out</button>
    </form>
@else
    <button class="btn btn-outline-danger fs-3 auth-hover-hide" type="button">Guest</button>
    <a class="btn btn-success fs-3 auth-hover-show" href="{{ route('login') }}">Login</a>
@endif