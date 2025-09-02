@csrf
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">taskId</label>
    <input type="text" name="taskId" maxlength="11"
      class="form-control @error('taskId') is-invalid @enderror"
      value="{{ old('taskId', $task_list->taskId ?? '') }}" required>
    @error('taskId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">scheduleId</label>
    <input type="text" name="scheduleId" maxlength="20"
      class="form-control @error('scheduleId') is-invalid @enderror"
      value="{{ old('scheduleId', $task_list->scheduleId ?? '') }}" required>
    @error('scheduleId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">phaseId</label>
    <input type="text" name="phaseId" maxlength="20"
      class="form-control @error('phaseId') is-invalid @enderror"
      value="{{ old('phaseId', $task_list->phaseId ?? '') }}">
    @error('phaseId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">taskStatus</label>
    @php($val = old('taskStatus', $task_list->taskStatus ?? '0'))
    <select name="taskStatus" class="form-select @error('taskStatus') is-invalid @enderror">
      <option value="0" @selected($val==='0')>0 - Pending</option>
      <option value="1" @selected($val==='1')>1 - Selesai</option>
    </select>
    @error('taskStatus') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">userName</label>
    <input type="text" name="userName" maxlength="60"
      class="form-control @error('userName') is-invalid @enderror"
      value="{{ old('userName', $task_list->userName ?? '') }}">
    @error('userName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('task-list.index') }}" class="btn btn-secondary">Batal</a>
</div>
