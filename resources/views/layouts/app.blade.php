<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Guard Patrol')</title>

  {{-- Bootstrap 5.2.3 (CSS) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

  {{-- Bootstrap Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  @stack('styles')
  <style>
    body { min-height: 100vh; }
    .sidebar {
      min-height: calc(100vh - 56px); /* tinggi layar - tinggi navbar */
    }
    @media (max-width: 991.98px) {
      /* Sidebar jadi offcanvas pada layar kecil */
      .sidebar-offcanvas { width: 260px; }
    }
  </style>
</head>
<body class="d-flex flex-column">

  {{-- Topbar --}}
  @include('layouts.topmenu')

  <div class="container-fluid">
    <div class="row">

      {{-- Sidebar (offcanvas untuk mobile, fixed untuk desktop) --}}
      <nav id="appSidebar" class="col-lg-2 d-lg-block bg-light border-end sidebar p-0">
        <div class="d-lg-none">
          {{-- Versi offcanvas untuk layar kecil --}}
          <div class="offcanvas offcanvas-start sidebar-offcanvas" tabindex="-1" id="offcanvasSidebar">
            <div class="offcanvas-header">
              <h5 class="offcanvas-title">Menu</h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
              @include('layouts.sidebar')
            </div>
          </div>
        </div>

        <div class="d-none d-lg-block h-100">
          @include('layouts.sidebar')
        </div>
      </nav>

      {{-- Konten utama --}}
      <main class="col-lg-10 ms-auto px-3 py-3">
        <h3 class="mb-3">@yield('page_heading', 'Guard Patrol')</h3>

        @if(session('ok'))
          <div class="alert alert-success">{{ session('ok') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
      </main>
    </div>
  </div>

  {{-- Footer --}}
  @include('layouts.footer')

  {{-- Bootstrap 5.2.3 (JS Bundle) --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
          crossorigin="anonymous"></script>

  <script>
    // Tombol toggle sidebar (untuk mobile)
    document.addEventListener('DOMContentLoaded', () => {
      const toggleBtn = document.getElementById('sidebarToggle');
      if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
          const oc = document.getElementById('offcanvasSidebar');
          const offcanvas = new bootstrap.Offcanvas(oc);
          offcanvas.toggle();
        });
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
