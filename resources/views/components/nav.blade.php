<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          
          <a href="{{route("profile")}}" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> Mi perfil
          </a>
          <div class="dropdown-divider"></div>
          <a href="logout" onclick="event.preventDefault();document.getElementById('form_logout').submit()" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> Cerrar la sesión
          </a>

          <form action="{{route("logout")}}" method="post" id="form_logout">
            <input type="hidden" name="token_" value="{{$this->Csrf()}}">
          </form>
          
          <div class="dropdown-divider"></div>
          <p class="dropdown-item dropdown-footer">{{$this->user()[0]->nombre_rol}}</p>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>