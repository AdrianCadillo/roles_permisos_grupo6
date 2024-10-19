<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route("home")}}" class="brand-link">
      <img src="{{assets("dist/img/AdminLTELogo.png")}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">TecnologySoft</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{assets("dist/img/user2-160x160.jpg")}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          @if (count($this->user()) >0)
          <a href="#" class="d-block">{{$this->user()[0]->name}}</a>
          @else 
          <a href="#" class="d-block"></a>
          @endif
        </div>
      </div>
 

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
           
          @if ($this->can("usuario.index"))
          <li class="nav-item">
            <a href="{{route("users")}}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Usuarios
              </p>
            </a>
          </li>
          @endif

          @if ($this->can("rol.index"))
          <li class="nav-item">
            <a href="{{route("roles")}}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
               Roles
              </p>
            </a>
          </li>
          @endif

          @if ($this->can("permiso.index"))
          <li class="nav-item">
            <a href="{{route("permisos")}}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
               Permisos
              </p>
            </a>
          </li>
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>