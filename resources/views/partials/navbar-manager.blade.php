<nav class="navbar navbar-expand-sm bg-info navbar-dark">
    <a class="navbar-brand" href="{{ url('/') }}">
        管理介面
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link {{ active(['admin.users']) }}"
                    href="{{ route('admin.users') }}"><span class="oi oi-person"></span> 管理帳號</a>
            </li>
            <li class="nav-item dropdown {{ active(['admin.probecube', 'admin.independent']) }}">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    設備管理
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('admin.probecube') }}">Probecube</a>
                    <a class="dropdown-item" href="{{ route('admin.independent') }}">Independent</a>
                </div>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            @if (Auth::check())
                <li><a href="{{ route('admin.logout') }}">登出</a></li>
            @endif
        </ul>
    </div>
</nav>