<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
  <div class="container-fluid">
    <button id="sidebarToggle" class="btn btn-outline-light d-lg-none me-2" type="button" aria-label="Toggle sidebar">
      <i class="bi bi-list"></i>
    </button>

    <a class="navbar-brand" href="{{ url('/') }}">
      <i class="bi bi-shield-lock-fill me-1"></i> Guard Patrol
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"
            aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="topNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}"><i class="bi bi-house-door me-1"></i> Home</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('tb_activity*') ? 'active' : '' }}" href="{{ url('/tb_activity') }}">Activity</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('tb_report*') ? 'active' : '' }}" href="{{ url('/tb_report') }}">Report</a></li>
      </ul>

      <ul class="navbar-nav ms-auto">
        {{-- Placeholder user dropdown --}}
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-5 me-1"></i> {{ auth()->user()->name ?? 'Guest' }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ url('/profile') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
            <li><a class="dropdown-item" href="{{ url('/settings') }}"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
            <li><hr class="dropdown-divider"></li>
            @auth
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                </form>
              </li>
            @else
              <li><a class="dropdown-item" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a></li>
            @endauth
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
