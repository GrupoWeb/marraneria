<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="./">
        <img class="navbar-brand-full" src="img/logo.jpg" width="89" height="50" >
        <img class="navbar-brand-minimized" src="img/logo.jpg" width="30" height="30" >
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="nav navbar-nav ml-auto mr-3">
        <li class="nav-item dropdown">
            <a class="nav-link nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
               aria-expanded="false">
                <!-- <span>{{ auth()->user()->username!=null ? auth()->user()->username : "Administrator" }}</span> -->
                <img class="img-avatar" src="img/avatars/avatar_user.png">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-center">
                    <strong>Herramientas</strong>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">
                    <i class="fa fa-shield"></i> Cambiar Clave</a>
                <a class="dropdown-item" href="/logout">
                    <i class="fa fa-lock"></i> Salir</a>
            </div>
        </li>
    </ul>
</header>
