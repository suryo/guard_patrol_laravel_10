@extends('layouts.app')

@php
  use Carbon\Carbon;
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-2">
  <h5 class="mb-0">Report</h5>
  <div class="d-flex gap-2">
    {{-- tombol tambah --}}
    <a class="btn btn-primary" href="{{ route('report.create') }}" title="Tambah">
      <i class="bi bi-plus-lg me-1"></i> Tambah
    </a>
    {{-- tombol filter (offcanvas sederhana) --}}
    <button class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filterBox" aria-expanded="{{ request()->filled('q') || request()->filled('date') ? 'true' : 'false' }}">
      <i class="bi bi-funnel"></i>
    </button>
  </div>
</div>

<div id="filterBox" class="collapse {{ request()->filled('q') || request()->filled('date') ? 'show' : '' }}">
  <form class="row g-2 mb-3">
    <div class="col-md-4">
      <input name="q" class="form-control" placeholder="Cari reportId / personId / checkpoint" value="{{ request('q') }}">
    </div>
    <div class="col-md-3">
      <input type="date" name="date" class="form-control" value="{{ request('date') }}">
    </div>
    <div class="col-md-2">
      <button class="btn btn-outline-primary w-100">Filter</button>
    </div>
    @if(request()->filled('q') || request()->filled('date'))
      <div class="col-md-2">
        <a class="btn btn-outline-secondary w-100" href="{{ route('report.index') }}">Reset</a>
      </div>
    @endif
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="list-group list-group-flush" id="report-accordion">
      @forelse($dates as $row)
        @php
          $d = $row->reportDate;
          $pretty = Carbon::parse($d)->translatedFormat('d F Y');
          $collapseId = 'day-'.\Illuminate\Support\Str::slug($d);
          $phases = $details->get($d, collect())->keys(); // collection of activityId
        @endphp

        <div class="list-group-item">
          <div class="d-flex align-items-center justify-content-between">
            <button class="btn btn-link text-decoration-none p-0" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false">
              <span class="fw-semibold">{{ $pretty }}</span>
              <span class="text-muted ms-2">({{ $row->phase_count }} Phase)</span>
            </button>
            <button class="btn btn-sm btn-outline-light border-0" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
              <i class="bi bi-chevron-down"></i>
            </button>
          </div>

          <div id="{{ $collapseId }}" class="collapse mt-2" data-bs-parent="#report-accordion">
            {{-- daftar phase pada tanggal ini --}}
            @forelse($details->get($d, collect()) as $activityId => $items)
              <div class="border rounded p-2 mb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <div class="fw-semibold">Phase {{ $loop->iteration }} <span class="text-muted">(#{{ $activityId }})</span></div>
                  <span class="badge bg-secondary">{{ $items->count() }} checkpoint</span>
                </div>

                <div class="table-responsive">
                  <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                      <tr>
                        <th style="width:105px;">Time</th>
                        <th style="width:100px;">Person</th>
                        <th>Checkpoint</th>
                        <th class="text-nowrap" style="width:100px;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($items as $i)
                        <tr>
                          <td class="text-nowrap">{{ $i->reportTime }}</td>
                          <td>{{ $i->personId }}</td>
                          <td class="text-truncate" style="max-width:520px">{{ $i->checkpointName }}</td>
                          <td class="text-nowrap">
                            <a href="{{ route('report.show', $i) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            <a href="{{ route('report.edit', $i) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @empty
              <div class="text-muted">Tidak ada data pada tanggal ini.</div>
            @endforelse
          </div>
        </div>
      @empty
        <div class="list-group-item text-center text-muted">Belum ada data</div>
      @endforelse
    </div>
  </div>
</div>

<div class="mt-3">
  {{ $dates->links() }}
</div>
@endsection
