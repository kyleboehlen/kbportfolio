<ul class="nav flex-column">
    @foreach($action_nav_opts as $action_nav_opt)
        <li class="nav-item mt-3 {{ $route == $action_nav_opt['route'] ? 'active' : '' }}">
            <a class="nav-link fs-3 d-flex align-items-center" aria-current="page" href="{{ route($action_nav_opt['route']) }}">
                <img src="/storage/icons/{{ $action_nav_opt['icon'] }}.png" />
                <p class="flex-grow-1">{{ $action_nav_opt['text'] }}</p>
            </a>
        </li>
    @endforeach
</ul>