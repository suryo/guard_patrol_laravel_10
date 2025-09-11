{{-- Modal: Tambah Template Schedule --}}
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
