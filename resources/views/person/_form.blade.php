@csrf
<div class="row g-3">
  <div class="col-md-2">
    <label class="form-label">personId</label>
    <input type="text" name="personId" maxlength="100"
           class="form-control @error('personId') is-invalid @enderror"
           value="{{ old('personId', $person->personId ?? '') }}" required>
    @error('personId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-5">
    <label class="form-label">personName</label>
    <input type="text" name="personName" maxlength="60"
           class="form-control @error('personName') is-invalid @enderror"
           value="{{ old('personName', $person->personName ?? '') }}" required>
    @error('personName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">userName</label>
    <input type="text" name="userName" maxlength="60"
           class="form-control @error('userName') is-invalid @enderror"
           value="{{ old('userName', $person->userName ?? '') }}">
    @error('userName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">isDeleted</label>
    <select name="isDeleted" class="form-select @error('isDeleted') is-invalid @enderror">
      @php($val = old('isDeleted', $person->isDeleted ?? '0'))
      <option value="0" @selected($val==='0')>0</option>
      <option value="1" @selected($val==='1')>1</option>
    </select>
    @error('isDeleted') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('person.index') }}" class="btn btn-secondary">Batal</a>
</div>
