@extends('layouts.app')
@section('content')
<h5>Detail User</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $user->uid }}</dd>
      <dt class="col-sm-3">userId</dt><dd class="col-sm-9">{{ $user->userId }}</dd>
      <dt class="col-sm-3">userName</dt><dd class="col-sm-9">{{ $user->userName }}</dd>
      <dt class="col-sm-3">Level</dt><dd class="col-sm-9">{{ $user->userLevel }}</dd>
      <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $user->userEmail }}</dd>
      <dt class="col-sm-3">hashMobile</dt><dd class="col-sm-9">{{ $user->hashMobile }}</dd>
      <dt class="col-sm-3">hashWeb</dt><dd class="col-sm-9">{{ $user->hashWeb }}</dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('users.edit',$user) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
