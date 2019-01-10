<nav class="navbar navbar-expand-sm bg-secondary navbar-dark">
    <a class="navbar-brand" href="{{ url('/') }}">
        <img src="https://i.imgur.com/Gro4juQ.png" alt="g0v icon">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ active('list') }}">
                <a class="nav-link" href="{{ route('list') }}"><i class="fas fa-list-ul"></i> 站點列表</a>
            </li>
            <li class="nav-item {{ active('widget.index') }}">
                <a class="nav-link" href="{{ route('widget.index') }}"><i class="fas fa-tachometer-alt"></i> 小工具</a>
            </li>
            <li class="nav-item {{ active('recruit') }}">
                <a class="nav-link" href="{{ route('recruit') }}"><i class="fas fa-sign-in-alt"></i> 自造站點募集</a>
            </li>
            <li class="nav-item {{ active('datasource') }}">
                <a class="nav-link" href="{{ route('datasource') }}"><i class="fas fa-warehouse"></i> 資料API</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="{{ route('admin.index') }}"><i class="fas fa-wrench"></i> 管理</a>
            </li>
        </ul>
    </div>
</nav>