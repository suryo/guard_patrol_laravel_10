@csrf
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">activityId</label>
    <input type="text" name="activityId" maxlength="20"
      class="form-control @error('activityId') is-invalid @enderror"
      value="{{ old('activityId', $activity->activityId ?? '') }}" required>
    @error('activityId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">personId</label>
    <input type="text" name="personId" maxlength="2"
      class="form-control @error('personId') is-invalid @enderror"
      value="{{ old('personId', $activity->personId ?? '') }}" required>
    @error('personId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">scheduleId</label>
    <input type="text" name="scheduleId" maxlength="20"
      class="form-control @error('scheduleId') is-invalid @enderror"
      value="{{ old('scheduleId', $activity->scheduleId ?? '') }}">
    @error('scheduleId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">checkpointStart</label>
    <input type="text" name="checkpointStart" maxlength="60"
      class="form-control @error('checkpointStart') is-invalid @enderror"
      value="{{ old('checkpointStart', $activity->checkpointStart ?? '') }}">
    @error('checkpointStart') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">checkpointEnd</label>
    <input type="text" name="checkpointEnd" maxlength="60"
      class="form-control @error('checkpointEnd') is-invalid @enderror"
      value="{{ old('checkpointEnd', $activity->checkpointEnd ?? '') }}">
    @error('checkpointEnd') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">activityStart</label>
    <input type="datetime-local" name="activityStart"
      class="form-control @error('activityStart') is-invalid @enderror"
      value="{{ old('activityStart', isset($activity->activityStart)? \Carbon\Carbon::parse($activity->activityStart)->format('Y-m-d\TH:i') : '') }}">
    @error('activityStart') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">activityEnd</label>
    <input type="datetime-local" name="activityEnd"
      class="form-control @error('activityEnd') is-invalid @enderror"
      value="{{ old('activityEnd', isset($activity->activityEnd)? \Carbon\Carbon::parse($activity->activityEnd)->format('Y-m-d\TH:i') : '') }}">
    @error('activityEnd') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">activityStatus</label>
    @php($val = old('activityStatus', $activity->activityStatus ?? '0'))
    <select name="activityStatus" class="form-select @error('activityStatus') is-invalid @enderror">
      <option value="0" @selected($val==='0')>0 - planned</option>
      <option value="1" @selected($val==='1')>1 - done</option>
      <option value="2" @selected($val==='2')>2 - miss</option>
    </select>
    @error('activityStatus') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('activity.index') }}" class="btn btn-secondary">Batal</a>
</div>
