{{-- Preview isi template untuk modal --}}
<div class="modal-header">
  <h5 class="modal-title">Schedule Template</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
  <div class="mb-3">
    <div class="text-muted">Template Name</div>
    <div class="fs-5 fw-semibold">{{ $schedule_template->templateName }}</div>
  </div>

  {{-- Contoh struktur list group-task + task seperti mockup gambar --}}
  @php
    // pastikan relasi $schedule_template->taskDetails() sudah include pivot sortOrder dan relasi task/group bila ada
    $details = $schedule_template->taskDetails()
        ->with(['group','task']) // sesuaikan dengan relasi yang ada di model
        ->orderBy('tb_schedule_template_detail.sortOrder') // nama pivot table/kolom sesuaikan
        ->get();
    // Kelompokkan per group (Point 37, Point 38, dst) jika tersedia
    $grouped = $details->groupBy(fn($d) => optional($d->group)->groupName ?? 'Group');
  @endphp

  @foreach($grouped as $gName => $rows)
    <div class="border-bottom pb-3 mb-3">
      <div class="d-flex align-items-center justify-content-between">
        <div class="fs-6 fw-semibold">{{ $gName }}</div>
        <div class="small text-muted">
          {{-- jika ada timeStart/timeEnd di detail/group, tampilkan --}}
          @php
            $t0 = $rows->first();
            $ts = $t0->timeStart ?? optional($t0->group)->timeStart;
            $te = $t0->timeEnd   ?? optional($t0->group)->timeEnd;
          @endphp
          @if($ts || $te)
            <i class="bi bi-clock"></i> ({{ $ts ?? '??:??' }} - {{ $te ?? '??:??' }})
          @endif
        </div>
      </div>

      <div class="mt-2 d-flex flex-wrap gap-2">
        @foreach($rows as $r)
          <span class="badge rounded-pill text-bg-light border">
            {{ optional($r->task)->taskName ?? optional($r->task)->name ?? 'Task #'.$r->task_uid }}
          </span>
        @endforeach
      </div>
    </div>
  @endforeach
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
</div>
