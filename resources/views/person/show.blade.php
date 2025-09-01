@extends('layouts.app')
@section('content')
<h5>Detail Person</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $person->uid }}</dd>
      <dt class="col-sm-3">personId</dt><dd class="col-sm-9">{{ $person->personId }}</dd>
      <dt class="col-sm-3">personName</dt><dd class="col-sm-9">{{ $person->personName }}</dd>
      <dt class="col-sm-3">userName</dt><dd class="col-sm-9">{{ $person->userName }}</dd>
      <dt class="col-sm-3">isDeleted</dt><dd class="col-sm-9">{{ $person->isDeleted }}</dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('person.edit',$person) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('person.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
