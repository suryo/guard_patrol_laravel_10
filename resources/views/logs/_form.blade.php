@csrf
<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">activity</label>
    <input type="text" name="activity" maxlength="30"
      class="form-control @error('activity') is-invalid @enderror"
      value="{{ old('activity', $log->activity ?? '') }}">
    @error('activity') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">category</label>
    <input type="text" name="category" maxlength="30"
      class="form-control @error('category') is-invalid @enderror"
      value="{{ old('category', $log->category ?? '') }}">
    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">userName</label>
    <input type="text" name="userName" maxlength="60"
      class="form-control @error('userName') is-invalid @enderror"
      value="{{ old('userName', $log->userName ?? '') }}">
    @error('userName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12">
    <label class="form-label">note</label>
    <textarea name="note" rows="4"
      class="form-control @error('note') is-invalid @enderror"
    >{{ old('note', $log->note ?? '') }}</textarea>
    @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('logs.index') }}" class="btn btn-secondary">Batal</a>
</div>
