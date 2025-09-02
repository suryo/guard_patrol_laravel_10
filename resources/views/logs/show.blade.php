@extends('layouts.app')
@section('content')
<h5>Detail Log</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $log->uid }}</dd>
      <dt class="col-sm-3">activity</dt><dd class="col-sm-9">{{ $log->activity }}</dd>
      <dt class="col-sm-3">category</dt><dd class="col-sm-9">{{ $log->category }}</dd>
      <dt class="col-sm-3">userName</dt><dd class="col-sm-9">{{ $log->userName }}</dd>
      <dt class="col-sm-3">note</dt><dd class="col-sm-9"><pre class="mb-0">{{ $log->note }}</pre></dd>
      <dt class="col-sm-3">lastUpdated</dt><dd class="col-sm-9">{{ $log->lastUpdated }}</dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('logs.edit',$log) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('logs.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
