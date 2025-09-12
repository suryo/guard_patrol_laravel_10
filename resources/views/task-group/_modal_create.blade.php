<div class="modal fade" id="modalCreateGroup" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Tambah Task Group</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form id="formCreateGroup" action="{{ route('task-group.store') }}" method="post">
        @csrf
        <div class="modal-body">
          <div class="alert alert-danger d-none ajax-errors"></div>

          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Group ID <span class="text-danger">*</span></label>
              <input name="groupId" class="form-control" maxlength="30" required>
            </div>
            <div class="col-md-8">
              <label class="form-label">Group Name <span class="text-danger">*</span></label>
              <input name="groupName" class="form-control" maxlength="120" required>
            </div>
            <div class="col-12">
              <label class="form-label">Group Note</label>
              <textarea name="groupNote" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">User Name</label>
              <input name="userName" class="form-control" maxlength="60">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Simpan</button>
          <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
