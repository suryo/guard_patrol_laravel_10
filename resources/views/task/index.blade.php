@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Task</h5>
  <div class="d-flex gap-2">
    <a class="btn btn-primary" href="{{ route('task.create') }}">Tambah Task</a>
    <a class="btn btn-outline-primary" href="{{ route('task-template.create') }}">Tambah Template</a>
  </div>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-4">
    <input name="q" class="form-control" placeholder="Cari task / template"
           value="{{ $q ?? '' }}">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100">Filter</button>
  </div>
  @if(!empty($q))
    <div class="col-md-2">
      <a class="btn btn-outline-secondary w-100" href="{{ route('task.index') }}">Reset</a>
    </div>
  @endif
</form>

<div class="row g-3">
  {{-- KIRI: TASK LIST --}}
  <div class="col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Task List</strong>
        <div class="d-flex align-items-center gap-2">
          <a class="btn btn-sm btn-outline-secondary" title="Refresh" href="{{ request()->fullUrl() }}">
            <i class="bi bi-arrow-clockwise"></i>
          </a>
          <a class="btn btn-sm btn-primary" title="Tambah Task" href="{{ route('task.create') }}">
            <i class="bi bi-plus-lg"></i>
          </a>
        </div>
      </div>
      <div class="table-responsive" style="max-height: 70vh; overflow:auto;">
        <table class="table table-sm align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:36px"></th>
              <th>Task Name</th>
              <th class="text-end">Last Update</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tasks as $t)
              <tr>
                <td><input type="checkbox" class="form-check-input"></td>
                <td>
                  <a href="{{ route('task.show', $t) }}" class="text-decoration-none">
                    {{ $t->taskName }}
                  </a>
                  <div class="text-muted small">{{ $t->taskId }}</div>
                </td>
                <td class="text-end text-muted small">
                  {{ \Carbon\Carbon::parse($t->lastUpdated)->format('d/m/Y (H:i)') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-muted py-4">Belum ada data</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer">
        {{ $tasks->withQueryString()->links() }}
      </div>
    </div>
  </div>

  {{-- KANAN: TEMPLATE LIST --}}
  <div class="col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Template List</strong>
        <div class="d-flex align-items-center gap-2">
          <a class="btn btn-sm btn-outline-secondary" title="Refresh" href="{{ request()->fullUrl() }}">
            <i class="bi bi-arrow-clockwise"></i>
          </a>
          <a class="btn btn-sm btn-primary" title="Tambah Template" href="{{ route('task-template.create') }}">
            <i class="bi bi-plus-lg"></i>
          </a>
        </div>
      </div>

      <div class="table-responsive" style="max-height: 70vh; overflow:auto;">
        <table class="table table-sm align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:36px"></th>
              <th>Template Name</th>
              <th>Task</th>
            </tr>
          </thead>
          <tbody>
            @forelse($templates as $tpl)
              @php
                $names = array_filter(explode('||', (string) $tpl->task_names));
              @endphp
              <tr>
                <td><input type="checkbox" class="form-check-input"></td>
                <td>
                  <div class="fw-semibold">{{ $tpl->templateName }}</div>
                  <div class="text-muted small">{{ \Carbon\Carbon::parse($tpl->lastUpdated)->format('d/m/Y (H:i)') }}</div>
                </td>
                <td>
                  <div class="d-flex flex-wrap gap-2">
                    @forelse($names as $nm)
                      <span class="badge rounded-pill bg-light border text-dark">{{ $nm }}</span>
                    @empty
                      <span class="text-muted small">—</span>
                    @endforelse
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-muted py-4">Belum ada template</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="card-footer small text-muted">
        Menampilkan {{ count($templates) }} template
      </div>
    </div>
  </div>
</div>
@endsection
