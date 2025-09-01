@extends('layouts.app')
@section('content')
<h5>Tambah Person</h5>
<form method="post" action="{{ route('person.store') }}" class="row g-2">
  @csrf
  <div class="col-md-2"><label class="form-label">personId</label>
    <input name="personId" class="form-control" value="{{ old('personId') }}" required>
  </div>
  <div class="col-md-4"><label class="form-label">personName</label>
    <input name="personName" class="form-control" value="{{ old('personName') }}" required>
  </div>
  <div class="col-md-4"><label class="form-label">userName</label>
    <input name="userName" class="form-control" value="{{ old('userName') }}">
  </div>
  <div class="col-md-2"><label class="form-label">isDeleted</label>
    <select name="isDeleted" class="form-select">
      <option value="0">0</option>
      <option value="1">1</option>
    </select>
  </div>
  <div class="col-12">
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('person.index') }}" class="btn btn-secondary">Batal</a>
  </div>
</form>
@endsection
