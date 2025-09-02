@extends('layouts.app')
@section('content')
<h5>Detail Phase</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $phase->uid }}</dd>
      <dt class="col-sm-3">phaseId</dt><dd class="col-sm-9">{{ $phase->phaseId }}</dd>
      <dt class="col-sm-3">phaseDate</dt><dd class="col-sm-9">{{ $phase->phaseDate }}</dd>
      <dt class="col-sm-3">lastUpdated</dt><dd class="col-sm-9">{{ $phase->lastUpdated }}</dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('phase.edit',$phase) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('phase.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
