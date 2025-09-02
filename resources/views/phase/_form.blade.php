@csrf
<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">phaseId</label>
    <input type="text" name="phaseId" maxlength="20"
      class="form-control @error('phaseId') is-invalid @enderror"
      value="{{ old('phaseId', $phase->phaseId ?? '') }}" required>
    @error('phaseId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">phaseDate</label>
    <input type="date" name="phaseDate"
      class="form-control @error('phaseDate') is-invalid @enderror"
      value="{{ old('phaseDate', isset($phase->phaseDate)? \Carbon\Carbon::parse($phase->phaseDate)->format('Y-m-d') : '') }}">
    @error('phaseDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('phase.index') }}" class="btn btn-secondary">Batal</a>
</div>
