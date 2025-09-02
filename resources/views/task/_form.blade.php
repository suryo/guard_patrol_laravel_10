@csrf
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">taskId</label>
    <input type="text" name="taskId" maxlength="11"
      class="form-control @error('taskId') is-invalid @enderror"
      value="{{ old('taskId', $task->taskId ?? '') }}" required>
    @error('taskId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-5">
    <label class="form-label">taskName</label>
    <input type="text" name="taskName" maxlength="60"
      class="form-control @error('taskName') is-invalid @enderror"
      value="{{ old('taskName', $task->taskName ?? '') }}" required>
    @error('taskName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">userName</label>
    <input type="text" name="userName" maxlength="60"
      class="form-control @error('userName') is-invalid @enderror"
      value="{{ old('userName', $task->userName ?? '') }}">
    @error('userName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12">
    <label class="form-label">taskNote</label>
    <textarea name="taskNote" rows="3"
      class="form-control @error('taskNote') is-invalid @enderror"
    >{{ old('taskNote', $task->taskNote ?? '') }}</textarea>
    @error('taskNote') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('task.index') }}" class="btn btn-secondary">Batal</a>
</div>
