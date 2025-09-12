{{-- resources/views/schedule/_modal_add_template.blade.php --}}
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
                            <input type="text" name="templateName" class="form-control" required
                                value="{{ old('templateName') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Template ID <small class="text-muted">(kosongkan untuk
                                    otomatis)</small></label>
                            <input type="text" name="templateId" class="form-control" value="{{ old('templateId') }}"
                                placeholder="SCHDL-TMPLT-00000">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Person ID</label>
                            <input type="text" name="personId" class="form-control" value="{{ old('personId') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Time Start</label>
                            <input type="datetime-local" name="timeStart" class="form-control"
                                value="{{ old('timeStart') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Time End</label>
                            <input type="datetime-local" name="timeEnd" class="form-control"
                                value="{{ old('timeEnd') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Task Group Details</label>
                            {{-- Hanya satu option per Pos/Group --}}
                            <select name="group_uids[]" class="form-select" multiple size="10">
  @foreach(($taskGroups ?? collect()) as $g)
    @php
      $taskNames = $g->details
          ->map(fn($d) => optional($d->task)->display_name ?? optional($d->task)->taskName ?? optional($d->task)->name)
          ->filter()
          ->values();

      $preview = $taskNames->take(5)->implode(', ');
      $more    = $taskNames->count() > 5 ? ' +' . ($taskNames->count() - 5) . ' lagi' : '';
      $title   = $taskNames->implode("\n");
    @endphp
    <option value="{{ $g->uid }}" title="{{ $title }}">
      {{ $g->display_name }} @if($taskNames->isNotEmpty()) ({{ $preview }}{{ $more }}) @endif
    </option>
  @endforeach
</select>

                            <div class="form-text">Gunakan Ctrl/Shift untuk memilih beberapa. Arahkan mouse untuk
                                melihat semua task (tooltip).</div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <select name="task_detail_uids[]" class="form-select" multiple size="8">
                        @forelse(($taskDetails ?? collect()) as $td)
                            @php
                                // fallback berurutan: sesuaikan jika kamu tahu nama kolom aslinya
                                $label =
                                    $td->groupName ??
                                    ($td->detailName ?? ($td->taskName ?? ($td->name ?? 'Detail #' . $td->uid)));
                            @endphp
                            <option value="{{ $td->uid }}" @selected(collect(old('task_detail_uids', []))->contains($td->uid))>
                                {{ $label }} — {{ $td->uid }}
                            </option>
                        @empty
                            <option disabled>Belum ada Task Group Detail</option>
                        @endforelse
                    </select>



                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Template</button>
                </div>
            </form>
        </div>
    </div>
</div>
