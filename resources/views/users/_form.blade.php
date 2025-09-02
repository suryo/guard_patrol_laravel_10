@csrf
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">userId</label>
    <input type="text" name="userId" maxlength="8"
      class="form-control @error('userId') is-invalid @enderror"
      value="{{ old('userId', $user->userId ?? '') }}" required>
    @error('userId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">userName</label>
    <input type="text" name="userName" maxlength="60"
      class="form-control @error('userName') is-invalid @enderror"
      value="{{ old('userName', $user->userName ?? '') }}" required>
    @error('userName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">userLevel</label>
    @php($val = old('userLevel', $user->userLevel ?? 'U'))
    <select name="userLevel" class="form-select @error('userLevel') is-invalid @enderror" required>
      <option value="A" @selected($val==='A')>A (Admin)</option>
      <option value="S" @selected($val==='S')>S (Supervisor)</option>
      <option value="U" @selected($val==='U')>U (User)</option>
    </select>
    @error('userLevel') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      userPassword
      @isset($user)<small class="text-muted">(kosongkan jika tidak ganti)</small>@endisset
    </label>
    <input type="password" name="userPassword" minlength="6"
      class="form-control @error('userPassword') is-invalid @enderror">
    @error('userPassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">userEmail</label>
    <input type="email" name="userEmail" maxlength="120"
      class="form-control @error('userEmail') is-invalid @enderror"
      value="{{ old('userEmail', $user->userEmail ?? '') }}">
    @error('userEmail') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">hashMobile</label>
    <input type="text" name="hashMobile" maxlength="255"
      class="form-control @error('hashMobile') is-invalid @enderror"
      value="{{ old('hashMobile', $user->hashMobile ?? '') }}">
    @error('hashMobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">hashWeb</label>
    <input type="text" name="hashWeb" maxlength="255"
      class="form-control @error('hashWeb') is-invalid @enderror"
      value="{{ old('hashWeb', $user->hashWeb ?? '') }}">
    @error('hashWeb') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
</div>
