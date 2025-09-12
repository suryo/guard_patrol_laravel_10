<div class="list-group list-group-flush rounded-0">

    {{-- "Dashboard" diarahkan ke person.index sesuai redirect di "/" --}}

    <a href="{{ route('group.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('group.*') ? 'active' : '' }}">
        <i class="bi bi-alarm me-2"></i> Group Waktu
    </a>

    <a href="{{ route('checkpoint.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('checkpoint.*') ? 'active' : '' }}">
        <i class="bi bi-geo-alt-fill me-2"></i> Checkpoint
    </a>

    <a href="{{ route('task-group.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('task-group.*') ? 'active' : '' }}">
        <i class="bi bi-activity me-2"></i> Task
    </a>

    <a href="{{ route('route-guard.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('route-guard.*') ? 'active' : '' }}">
        <i class="bi bi-shield me-2"></i> Guard
    </a>

    <a href="{{ route('report.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('report.*') ? 'active' : '' }}">
        <i class="bi bi-graph-up-arrow me-2"></i> Report
    </a>

    <a href="{{ route('schedule.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('schedule.*') ? 'active' : '' }}">
        <i class="bi bi-calendar2-week me-2"></i> Schedule
    </a>

    <a href="{{ route('task.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('task.*') ? 'active' : '' }}">
        <i class="bi bi-check2-square me-2"></i> Task
    </a>

    <a href="{{ route('logs.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('logs.*') ? 'active' : '' }}">
        <i class="bi bi-journal-text me-2"></i> Logs
    </a>

    <a href="{{ route('users.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <i class="bi bi-person-gear me-2"></i> Users
    </a>

    <a href="" class="list-group-item list-group-item-action d-flex align-items-center">
        <i class="bi bi-geo-alt-fill me-2"></i> Logout
    </a>





    {{-- <a href="{{ route('person.index') }}"
     class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('person.*') ? 'active' : '' }}">
    <i class="bi bi-people-fill me-2"></i> Person
  </a>


  <a href="{{ route('activity.index') }}"
     class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('activity.*') ? 'active' : '' }}">
    <i class="bi bi-clipboard-check me-2"></i> Activity
  </a>



  <a href="{{ route('task-template.index') }}"
     class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('task-template.*') ? 'active' : '' }}">
    <i class="bi bi-journal-code me-2"></i> Task Template
  </a>

  <a href="{{ route('task-list.index') }}"
     class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('task-list.*') ? 'active' : '' }}">
    <i class="bi bi-card-checklist me-2"></i> Task List
  </a>




  <a href="{{ route('schedule-template.index') }}"
     class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('schedule-template.*') ? 'active' : '' }}">
    <i class="bi bi-calendar3-range me-2"></i> Schedule Template
  </a>

  <a href="{{ route('person-mapping.index') }}"
     class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('person-mapping.*') ? 'active' : '' }}">
    <i class="bi bi-diagram-3 me-2"></i> Person Mapping
  </a>

  <a href="{{ route('phase.index') }}"
     class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('phase.*') ? 'active' : '' }}">
    <i class="bi bi-layers-half me-2"></i> Phase
  </a>



  <div class="list-group-item fw-semibold text-muted">Laporan</div>
  <a href="{{ route('laporan.index') }}"
     class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-line me-2"></i> Laporan
  </a> --}}

</div>
