@csrf
<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Group ID <span class="text-danger">*</span></label>
    <input type="text" name="groupId" class="form-control @error('groupId') is-invalid @enderror"
           value="{{ old('groupId', $group->groupId) }}" maxlength="40" required>
    @error('groupId')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-8">
    <label class="form-label">Group Name <span class="text-danger">*</span></label>
    <input type="text" name="groupName" class="form-control @error('groupName') is-invalid @enderror"
           value="{{ old('groupName', $group->groupName) }}" maxlength="100" required>
    @error('groupName')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">Time Start</label>
    <input type="time" name="timeStart" class="form-control @error('timeStart') is-invalid @enderror"
           value="{{ old('timeStart', $group->timeStart ? \Illuminate\Support\Str::of($group->timeStart)->limit(5,'') : '') }}">
    @error('timeStart')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-2">
    <label class="form-label">Time End</label>
    <input type="time" name="timeEnd" class="form-control @error('timeEnd') is-invalid @enderror"
           value="{{ old('timeEnd', $group->timeEnd ? \Illuminate\Support\Str::of($group->timeEnd)->limit(5,'') : '') }}">
    @error('timeEnd')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  <div class="col-12">
    <label class="form-label">Note</label>
    <textarea name="note" rows="3" class="form-control @error('note') is-invalid @enderror"
              maxlength="1000">{{ old('note', $group->note) }}</textarea>
    @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  <div class="col-12 d-flex gap-2">
    <button class="btn btn-primary">{{ $submitText ?? 'Simpan' }}</button>
    <a href="{{ route('group.index') }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</div>
