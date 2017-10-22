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
            <li class="nav-item active">
                <a class="nav-link {{ active(['manager.users']) }}" 
                    href="{{ route('manager.users') }}">使用者帳號</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ active(['manager.probecube']) }}" 
                    href="{{ route('manager.probecube') }}">Probecube</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ active(['manager.independent']) }}" 
                    href="{{ route('manager.independent') }}">Independent</a>
            </li>
        </ul>
        
        <ul class="navbar-nav ml-auto">
            @if (Auth::check())
                <li><a href="{{ route('logout') }}">登出</a></li>
            @endif
        </ul>
    </div>
</nav>