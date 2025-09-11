{{-- Modal: Pilih / Edit Phase --}}
<div class="modal fade" id="modalPickPhase" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formPickPhase" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="mp-title">Pilih Phase</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="mp-group">
        <div class="mb-3">
          <label class="form-label">Tanggal Phase</label>
          <input type="date" id="mp-date" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Phase</label>
          <select id="mp-phase" class="form-select" required>
            <option value="">-- pilih --</option>
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
      <input type="hidden" id="mp-mode" value="create">
      <input type="hidden" id="mp-link">
      <input type="hidden" id="mp-group">
    </form>
  </div>
</div>
