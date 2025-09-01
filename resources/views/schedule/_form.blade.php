@csrf
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">scheduleId</label>
    <input type="text" name="scheduleId" maxlength="20"
      class="form-control @error('scheduleId') is-invalid @enderror"
      value="{{ old('scheduleId', $schedule->scheduleId ?? '') }}" required>
    @error('scheduleId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">personId</label>
    <input type="text" name="personId" maxlength="2"
      class="form-control @error('personId') is-invalid @enderror"
      value="{{ old('personId', $schedule->personId ?? '') }}" required>
    @error('personId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">mappingId</label>
    <input type="text" name="mappingId" maxlength="5"
      class="form-control @error('mappingId') is-invalid @enderror"
      value="{{ old('mappingId', $schedule->mappingId ?? '') }}">
    @error('mappingId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">activityId</label>
    <input type="text" name="activityId" maxlength="20"
      class="form-control @error('activityId') is-invalid @enderror"
      value="{{ old('activityId', $schedule->activityId ?? '') }}">
    @error('activityId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">checkpointName</label>
    <input type="text" name="checkpointName" maxlength="60"
      class="form-control @error('checkpointName') is-invalid @enderror"
      value="{{ old('checkpointName', $schedule->checkpointName ?? '') }}">
    @error('checkpointName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">scheduleName</label>
    <input type="text" name="scheduleName" maxlength="60"
      class="form-control @error('scheduleName') is-invalid @enderror"
      value="{{ old('scheduleName', $schedule->scheduleName ?? '') }}">
    @error('scheduleName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">scheduleStart</label>
    <input type="datetime-local" name="scheduleStart"
      class="form-control @error('scheduleStart') is-invalid @enderror"
      value="{{ old('scheduleStart', isset($schedule->scheduleStart)? \Carbon\Carbon::parse($schedule->scheduleStart)->format('Y-m-d\TH:i') : '') }}">
    @error('scheduleStart') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">scheduleEnd</label>
    <input type="datetime-local" name="scheduleEnd"
      class="form-control @error('scheduleEnd') is-invalid @enderror"
      value="{{ old('scheduleEnd', isset($schedule->scheduleEnd)? \Carbon\Carbon::parse($schedule->scheduleEnd)->format('Y-m-d\TH:i') : '') }}">
    @error('scheduleEnd') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">scheduleDate</label>
    <input type="date" name="scheduleDate"
      class="form-control @error('scheduleDate') is-invalid @enderror"
      value="{{ old('scheduleDate', isset($schedule->scheduleDate)? \Carbon\Carbon::parse($schedule->scheduleDate)->format('Y-m-d') : '') }}">
    @error('scheduleDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">userName</label>
    <input type="text" name="userName" maxlength="60"
      class="form-control @error('userName') is-invalid @enderror"
      value="{{ old('userName', $schedule->userName ?? '') }}">
    @error('userName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Batal</a>
</div>
