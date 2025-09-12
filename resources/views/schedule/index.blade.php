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

    <div class="col-lg-4">
      @include('schedule._right_panel', [
        'date'     => $date,
        'hasGroup' => $hasGroup ?? false,
        'groups'   => $groups ?? collect(),
      ])
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
@endsection

@push('scripts')
  @vite(['resources/js/schedule/index.js'])
@endpush
