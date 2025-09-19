{{-- resources/views/schedule/index.blade.php --}}
@extends('layouts.app')

@section('content')
  @php
    use Illuminate\Support\Carbon;
    $monthStart = $month->copy()->startOfMonth();
    $monthEnd   = $month->copy()->endOfMonth();
    $startWeek  = $monthStart->copy()->startOfWeek(); // Senin
    $endWeek    = $monthEnd->copy()->endOfWeek();
  @endphp

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Schedule</h5>
    <a class="btn btn-primary" href="{{ route('schedule.create') }}">Tambah</a>
  </div>

  @include('schedule._filters', ['q' => $q, 'date' => $date, 'month' => $month])

  <div class="row">
    <!-- KIRI: Kalender & Template -->
    <div class="col-lg-8">
      @include('schedule._calendar', [
        'month'      => $month,
        'monthStart' => $monthStart,
        'startWeek'  => $startWeek,
        'endWeek'    => $endWeek,
        'q'          => $q,
        'date'       => $date,
        'schedules'  => $schedules
      ])

      @include('schedule._template_table', ['templates' => $templates])
    </div>

    <!-- KANAN: Panel Group → Phase → Activity (layout baru) -->
    <div class="col-lg-4">
      <!-- Toolbar kanan -->
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
        <h6 class="mb-0">Detail Per Group</h6>
        <div class="d-flex gap-2">
          <button id="btnExpandAll" class="btn btn-outline-primary btn-sm" type="button">Expand All</button>
          <button id="btnCollapseAll" class="btn btn-outline-secondary btn-sm" type="button">Collapse All</button>
        </div>
      </div>

      <!-- Wrapper untuk kartu-kartu group -->
      <div id="groupsWrap" class="vstack gap-3">
        @include('schedule._right_panel', [
          'date'     => $date,
          'hasGroup' => $hasGroup ?? false,
          'groups'   => $groups ?? collect(),
        ])
      </div>
    </div>
  </div>

  {{-- Modals --}}
  {{-- Modal Preview Template --}}
  <div class="modal fade" id="templateViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content" id="templateViewModalContent">
        {{-- akan diisi via AJAX --}}
      </div>
    </div>
  </div>

  @include('schedule._modal_add_template', ['taskDetails' => $taskDetails])
  @include('schedule._modal_assign_group', ['allGroups' => $allGroups])
  @include('schedule._modal_pick_phase')
  @include('schedule._modal_add_activity')

  @php
    // Siapkan data untuk JS dalam PHP biasa (tanpa arrow fn di Blade)
    $taskGroupsForJs = ($taskGroups ?? collect())->map(function ($g) {
        $details = ($g->details ?? collect())->map(function ($d) {
            return [
                'uid'       => $d->uid,
                'group_uid' => $d->group_uid,
                'task_uid'  => optional($d->task)->uid,
                'task_name' => optional($d->task)->taskName
                                ?? optional($d->task)->name
                                ?? ('Task #' . ($d->task_uid ?? '')),
                'sortOrder' => $d->sortOrder,
                'timeStart' => $d->timeStart ?? null,
                'timeEnd'   => $d->timeEnd   ?? null,
            ];
        })->values();

        return [
            'uid'     => $g->uid,
            'name'    => $g->groupName ?? $g->display_name ?? ('Group #' . $g->uid),
            'details' => $details,
        ];
    })->values();

    $taskDetailsFlatForJs = ($taskDetails ?? collect())->map(function ($d) {
        return [
            'uid'       => $d->uid,
            'group_uid' => $d->group_uid,
            'task_uid'  => $d->task_uid ?? optional($d->task)->uid,
            'task_name' => optional($d->task)->taskName
                            ?? optional($d->task)->name
                            ?? ('Task #' . ($d->task_uid ?? '')),
            'sortOrder' => $d->sortOrder,
        ];
    })->values();
  @endphp

  <script>
    window.SCHEDULE_DATA = {!! json_encode([
      'taskGroups'      => $taskGroupsForJs,
      'taskDetailsFlat' => $taskDetailsFlatForJs,
    ], JSON_UNESCAPED_UNICODE) !!};
  </script>
@endsection

@push('scripts')
 @vite([
    'resources/css/schedule.css',  
    'resources/js/schedule/index.js'
  ])

@endpush
