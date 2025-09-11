{{-- Modal: Assign Group ke Tanggal --}}
<div class="modal fade" id="modalAssignGroup" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Pilih Group Waktu <small class="text-muted" id="assign-date-label"></small>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <form id="assign-group-form">
        @csrf
        <input type="hidden" name="date" id="assign-date">
        <div class="modal-body">
          <div class="mb-2">
            <div class="form-text">
              Centang group yang ingin diaktifkan pada tanggal tersebut (urutan sesuai centang).
            </div>
          </div>

          <div class="border rounded p-2" style="max-height: 50vh; overflow:auto;">
            @forelse($allGroups as $g)
              <div class="form-check py-1">
                <input class="form-check-input group-opt" type="checkbox"
                       value="{{ $g->uid }}" id="g{{ $g->uid }}" name="group_uids[]">
                <label class="form-check-label d-flex justify-content-between" for="g{{ $g->uid }}">
                  <span class="me-2">{{ $g->groupName }}</span>
                  <small class="text-muted">{{ $g->timeStart }} - {{ $g->timeEnd }}</small>
                </label>
              </div>
            @empty
              <div class="text-muted">Belum ada group. Buat dulu di menu Group.</div>
            @endforelse
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
