<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="#" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
          <img src="{{ asset('assets/images/user2-160x160.jpg') }}" class="user-image rounded-circle shadow me-2"
            width="32" height="32" alt="User Image" />
          <span class="d-none d-md-inline fw-semibold">Admin</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="min-width: 220px;">
          <li class="px-3 py-2 text-center border-bottom">
            <img src="{{ asset('assets/images/user2-160x160.jpg') }}" class="rounded-circle mb-2" width="60" height="60"
              alt="User Image">
            <p class="mb-0 fw-bold">Admin</p>
            <small class="text-muted">Administrator</small>
          </li>
          <li>
            <a href="#" class="dropdown-item py-2">
              <i class="bi bi-gear me-2"></i> Settings
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a href="#" class="dropdown-item text-danger py-2">
              <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">
                  <i class="bi bi-box-arrow-right me-2"></i> Sign out
                </button>
              </form>
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>