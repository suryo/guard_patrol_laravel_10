@csrf
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">taskId</label>
    <input type="text" name="taskId" maxlength="11"
      class="form-control @error('taskId') is-invalid @enderror"
      value="{{ old('taskId', $task_template->taskId ?? '') }}" required>
    @error('taskId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-5">
    <label class="form-label">taskName</label>
    <input type="text" name="taskName" maxlength="60"
      class="form-control @error('taskName') is-invalid @enderror"
      value="{{ old('taskName', $task_template->taskName ?? '') }}" required>
    @error('taskName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">taskNote</label>
    <input type="text" name="taskNote" maxlength="120"
      class="form-control @error('taskNote') is-invalid @enderror"
      value="{{ old('taskNote', $task_template->taskNote ?? '') }}">
    @error('taskNote') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('task-template.index') }}" class="btn btn-secondary">Batal</a>
</div>
