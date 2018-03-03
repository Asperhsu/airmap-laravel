<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <a class="navbar-brand" href="{{ url('/') }}">
        {{ config('app.name', 'Laravel') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('v4.map') }}">地圖</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('v4.list') }}">站點列表</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('recruit') }}">自造站點募集</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('dialy-gif') }}">截圖動畫</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('about') }}">關於</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="{{ route('admin.index') }}">管理</a>
            </li>
        </ul>
    </div>
</nav>