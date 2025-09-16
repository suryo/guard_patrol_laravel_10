{{-- Modal: Tambah Activity ke Phase --}}
<div class="modal fade" id="modalAddActivity" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <form id="formAddActivity" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add Activity ke Phase</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="maa-phase-uid">

        <div class="row g-3">
          <div class="col-md-5">
            <label class="form-label">Task Group (Pos)</label>
            <select id="maa-groups" class="form-select" multiple size="10"></select>
            <div class="form-text">Pilih 1 atau lebih group. Detail di kanan akan terfilter otomatis.</div>
          </div>

          <div class="col-md-7">
            <label class="form-label">Task Group Detail</label>
            <div id="maa-details" class="border rounded p-2" style="max-height: 45vh; overflow:auto">
              <div class="text-muted small">Pilih minimal 1 group untuk melihat detail.</div>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">Catatan (opsional)</label>
            <textarea id="maa-note" class="form-control" rows="2" placeholder="Catatan untuk semua activity yang ditambahkan (opsional)"></textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Activity</button>
      </div>
    </form>
  </div>
</div>
