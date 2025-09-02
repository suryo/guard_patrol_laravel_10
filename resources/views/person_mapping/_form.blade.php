@csrf
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">mappingId</label>
    <input type="text" name="mappingId" maxlength="5"
      class="form-control @error('mappingId') is-invalid @enderror"
      value="{{ old('mappingId', $person_mapping->mappingId ?? '') }}" required>
    @error('mappingId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">mappingName</label>
    <input type="text" name="mappingName" maxlength="60"
      class="form-control @error('mappingName') is-invalid @enderror"
      value="{{ old('mappingName', $person_mapping->mappingName ?? '') }}" required>
    @error('mappingName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">mappingTag</label>
    <input type="text" name="mappingTag" maxlength="20"
      class="form-control @error('mappingTag') is-invalid @enderror"
      value="{{ old('mappingTag', $person_mapping->mappingTag ?? '') }}">
    @error('mappingTag') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">userName</label>
    <input type="text" name="userName" maxlength="60"
      class="form-control @error('userName') is-invalid @enderror"
      value="{{ old('userName', $person_mapping->userName ?? '') }}">
    @error('userName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('person-mapping.index') }}" class="btn btn-secondary">Batal</a>
</div>
