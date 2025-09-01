@csrf
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">checkpointId</label>
    <input type="text" name="checkpointId" maxlength="20"
      class="form-control @error('checkpointId') is-invalid @enderror"
      value="{{ old('checkpointId', $checkpoint->checkpointId ?? '') }}" required>
    @error('checkpointId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-5">
    <label class="form-label">checkpointName</label>
    <input type="text" name="checkpointName" maxlength="60"
      class="form-control @error('checkpointName') is-invalid @enderror"
      value="{{ old('checkpointName', $checkpoint->checkpointName ?? '') }}" required>
    @error('checkpointName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">Latitude</label>
    <input type="number" step="0.000001" name="latitude"
      class="form-control @error('latitude') is-invalid @enderror"
      value="{{ old('latitude', $checkpoint->latitude ?? '') }}">
    @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">Longitude</label>
    <input type="number" step="0.000001" name="longitude"
      class="form-control @error('longitude') is-invalid @enderror"
      value="{{ old('longitude', $checkpoint->longitude ?? '') }}">
    @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12">
    <label class="form-label">Address</label>
    <input type="text" name="address" maxlength="255"
      class="form-control @error('address') is-invalid @enderror"
      value="{{ old('address', $checkpoint->address ?? '') }}">
    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12">
    <label class="form-label">Note</label>
    <textarea name="note" rows="3"
      class="form-control @error('note') is-invalid @enderror">{{ old('note', $checkpoint->note ?? '') }}</textarea>
    @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('checkpoint.index') }}" class="btn btn-secondary">Batal</a>
</div>
