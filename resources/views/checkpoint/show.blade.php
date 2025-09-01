@extends('layouts.app')
@section('content')
<h5>Detail Checkpoint</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $checkpoint->uid }}</dd>
      <dt class="col-sm-3">checkpointId</dt><dd class="col-sm-9">{{ $checkpoint->checkpointId }}</dd>
      <dt class="col-sm-3">checkpointName</dt><dd class="col-sm-9">{{ $checkpoint->checkpointName }}</dd>
      <dt class="col-sm-3">Latitude</dt><dd class="col-sm-9">{{ $checkpoint->latitude }}</dd>
      <dt class="col-sm-3">Longitude</dt><dd class="col-sm-9">{{ $checkpoint->longitude }}</dd>
      <dt class="col-sm-3">Address</dt><dd class="col-sm-9">{{ $checkpoint->address }}</dd>
      <dt class="col-sm-3">Note</dt><dd class="col-sm-9">{{ $checkpoint->note }}</dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('checkpoint.edit',$checkpoint) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('checkpoint.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
