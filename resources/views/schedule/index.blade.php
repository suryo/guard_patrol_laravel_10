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

<form class="row g-2 mb-3">
  <div class="col-md-3">
    <input name="q" class="form-control" placeholder="Cari scheduleId / personId / checkpoint" value="{{ $q }}">
  </div>
  <div class="col-md-3">
    <input type="date" name="d" class="form-control" value="{{ $date }}" placeholder="scheduleDate">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100">Filter</button>
  </div>
  @if($q || $date)
    <div class="col-md-2">
      <a class="btn btn-outline-secondary w-100" href="{{ route('schedule.index') }}">Reset</a>
    </div>
  @endif
</form>

<div class="row">
  {{-- KIRI: Kalender + Template Schedule --}}
  <div class="col-lg-8">

    {{-- Kalender bulanan --}}
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="fw-semibold">
          {{ $month->isoFormat('MMMM, YYYY') }}
        </div>
        <div class="btn-group">
          <a class="btn btn-sm btn-outline-secondary"
             href="{{ route('schedule.index', array_filter(['q'=>$q,'d'=>$date,'m'=>$month->copy()->subMonth()->format('Y-m')])) }}">
            ‹
          </a>
          <a class="btn btn-sm btn-outline-secondary"
             href="{{ route('schedule.index', array_filter(['q'=>$q,'d'=>$date,'m'=>$month->copy()->addMonth()->format('Y-m')])) }}">
            ›
          </a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0 table-bordered align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th>
              </tr>
            </thead>
            <tbody>
              @for($day = $startWeek->copy(); $day <= $endWeek; $day->addDay())
                @if($day->isMonday()) <tr> @endif
                  @php
                    $isOtherMonth = !$day->isSameMonth($monthStart);
                    $isSelected   = $day->toDateString() === $date;
                    $linkParams   = array_filter(['q'=>$q,'d'=>$day->toDateString(),'m'=>$month->format('Y-m')]);
                  @endphp
                  <td class="{{ $isOtherMonth ? 'text-muted bg-light' : '' }} {{ $isSelected ? 'table-primary fw-semibold' : '' }}"
                      style="height:68px; vertical-align:top;">
                    <div class="d-flex justify-content-between">
                      <small>{{ $day->day }}</small>
                      @php
                        $hasSched = $schedules->where('scheduleDate',$day->toDateString())->count() > 0;
                      @endphp
                      @if($hasSched && !$isSelected)
                        <span class="badge bg-secondary">•</span>
                      @endif
                    </div>
                    <div class="mt-2">
                      <a href="{{ route('schedule.index', $linkParams) }}" class="stretched-link"></a>
                    </div>
                  </td>
                @if($day->isSunday()) </tr> @endif
              @endfor
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Template Schedule --}}
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Template Schedule</span>
        <div>
          <button type="button" class="btn btn-sm btn-outline-secondary" title="Cari" data-bs-toggle="collapse" data-bs-target="#templateSearch">🔍</button>
          <button type="button" class="btn btn-sm btn-outline-primary"
            title="Tambah Template" data-bs-toggle="modal" data-bs-target="#modalAddTemplate">＋</button>
        </div>
      </div>

      <div id="templateSearch" class="collapse px-3 pt-3">
        <form method="get" class="row g-2">
          <div class="col-md-6">
            <input type="text" name="tq" class="form-control" placeholder="Cari template name" value="{{ request('tq') }}">
          </div>
          <div class="col-md-2">
            <button class="btn btn-outline-primary w-100">Filter</button>
          </div>
        </form>
      </div>

      <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:40px"></th>
              <th>Template Name</th>
              <th class="text-end">Last Update</th>
            </tr>
          </thead>
          <tbody>
            @forelse($templates as $t)
              <tr>
                <td><input type="checkbox" class="form-check-input"></td>
                <td class="text-nowrap">
                  <a href="{{ route('schedule-template.show', $t) }}" class="text-decoration-none">
                    {{ $t->templateName }}
                  </a>
                </td>
                <td class="text-end"><small>{{ \Illuminate\Support\Carbon::parse($t->lastUpdated)->format('d/m/Y (H:i)') }}</small></td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center text-muted">Belum ada template</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer py-2">
        {{ $templates->withQueryString()->links() }}
      </div>
    </div>

  </div>

  {{-- KANAN: Group accordion + tombol --}}
  <div class="col-lg-4">
    <div class="accordion" id="groupAccordion">
      @foreach(range(0,23) as $h)
        @php
          $label = sprintf('%02d:00 - %02d:59', $h, $h);
          $list  = $groups[$h] ?? collect();
          $cid   = "grp{$h}";
        @endphp
        <div class="accordion-item">
          <h2 class="accordion-header" id="heading-{{ $cid }}">
            <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $cid }}">
              <div class="d-flex justify-content-between w-100">
                <span>Group [{{ $label }}]</span>
                <span class="badge bg-secondary">{{ $list->count() }}</span>
              </div>
            </button>
          </h2>
          <div id="collapse-{{ $cid }}" class="accordion-collapse collapse" data-bs-parent="#groupAccordion">
            <div class="accordion-body p-2">
              @if($list->isEmpty())
                <div class="text-muted small">Belum ada schedule pada jam ini.</div>
              @else
                <ul class="list-group list-group-flush">
                  @foreach($list as $i)
                    <li class="list-group-item px-2 py-2">
                      <div class="d-flex justify-content-between">
                        <div class="me-2">
                          <div class="fw-semibold">{{ $i->checkpointName }}</div>
                          <div class="small text-muted">Person: {{ $i->personId }}</div>
                        </div>
                        <div class="text-end small">
                          <div>{{ substr($i->scheduleStart,0,5) }} – {{ substr($i->scheduleEnd,0,5) }}</div>
                          <a href="{{ route('schedule.show',$i) }}" class="text-decoration-none">Detail</a>
                        </div>
                      </div>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="d-grid gap-2 mt-3">
      <a href="{{ route('phase.create') }}" class="btn btn-primary">ADD PHASE</a>
      <button type="button" class="btn btn-outline-secondary" onclick="alert('Copy All Phase: coming soon')">COPY ALL PHASE</button>
    </div>
  </div>
</div>

{{-- MODAL: Tambah Template Schedule --}}
<div class="modal fade" id="modalAddTemplate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form method="post" action="{{ route('schedule-template.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Template Schedule</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          {{-- Info error validasi (jika ada) --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <div class="fw-semibold mb-1">Periksa kembali input Anda:</div>
              <ul class="mb-0">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label">Template Name <span class="text-danger">*</span></label>
              <input type="text" name="templateName" class="form-control" maxlength="150"
                     value="{{ old('templateName') }}" placeholder="Mis. AGAM 01.00 BLKG" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Template ID (opsional)</label>
              <input type="text" name="templateId" class="form-control"
                     value="{{ old('templateId') }}" placeholder="(auto jika dikosongkan)">
            </div>

            <div class="col-12">
              <label class="form-label d-flex align-items-center gap-2">
                Mapping IDs
                <small class="text-muted">(pisahkan dengan koma / baris baru)</small>
              </label>
              <textarea name="templateMapping" class="form-control" rows="2"
                        placeholder="mappingId1, mappingId2, ...">{{ old('templateMapping') }}</textarea>
            </div>

            <div class="col-12">
              <label class="form-label">Person (opsional)</label>
              <textarea name="templatePerson" class="form-control" rows="2"
                        placeholder="personId1, personId2, ...">{{ old('templatePerson') }}</textarea>
            </div>

            <div class="col-12">
              <label class="form-label">Checkpoint (opsional)</label>
              <textarea name="templateCheckpoint" class="form-control" rows="2"
                        placeholder="checkpoint A, checkpoint B, ...">{{ old('templateCheckpoint') }}</textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label">Start Time(s)</label>
              <textarea name="templateStart" class="form-control" rows="2"
                        placeholder="01:00, 02:00, ...">{{ old('templateStart') }}</textarea>
              <div class="form-text">Bisa banyak jam, pisahkan koma atau baris baru.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">End Time(s)</label>
              <textarea name="templateEnd" class="form-control" rows="2"
                        placeholder="01:59, 02:59, ...">{{ old('templateEnd') }}</textarea>
            </div>

            <div class="col-12">
              <label class="form-label">Task (opsional)</label>
              <textarea name="templateTask" class="form-control" rows="2"
                        placeholder="Instruksi tugas/phase yang terkait">{{ old('templateTask') }}</textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Template</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
