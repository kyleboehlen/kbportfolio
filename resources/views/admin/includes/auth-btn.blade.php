@if(\Auth::check())
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-outline-success fs-3 auth-hover-hide" type="submit">Admin</button>
        <button class="btn btn-danger fs-3 auth-hover-show" type="submit">Log Out</button>
    </form>
@else
    <a href="{{ route('login') }}" class="btn btn-outline-danger fs-3 auth-hover-hide">Guest</a>
    <a class="btn btn-success fs-3 auth-hover-show" href="{{ route('login') }}">Login</a>
@endif