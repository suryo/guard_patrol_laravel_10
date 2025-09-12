@csrf
<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Group ID <span class="text-danger">*</span></label>
    <input name="groupId" class="form-control" maxlength="30"
           value="{{ old('groupId', $task_group->groupId ?? '') }}" required>
    <div class="form-text">Unik, maksimal 30 karakter.</div>
  </div>

  <div class="col-md-8">
    <label class="form-label">Group Name <span class="text-danger">*</span></label>
    <input name="groupName" class="form-control" maxlength="120"
           value="{{ old('groupName', $task_group->groupName ?? '') }}" required>
  </div>

  <div class="col-12">
    <label class="form-label">Group Note</label>
    <textarea name="groupNote" class="form-control" rows="3">{{ old('groupNote', $task_group->groupNote ?? '') }}</textarea>
  </div>

  <div class="col-md-4">
    <label class="form-label">User Name</label>
    <input name="userName" class="form-control" maxlength="60"
           value="{{ old('userName', $task_group->userName ?? '') }}">
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('task-group.index') }}" class="btn btn-outline-secondary">Batal</a>
</div>
