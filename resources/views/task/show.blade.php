@extends('layouts.app')
@section('content')
<h5>Detail Task</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $task->uid }}</dd>
      <dt class="col-sm-3">taskId</dt><dd class="col-sm-9">{{ $task->taskId }}</dd>
      <dt class="col-sm-3">taskName</dt><dd class="col-sm-9">{{ $task->taskName }}</dd>
      <dt class="col-sm-3">userName</dt><dd class="col-sm-9">{{ $task->userName }}</dd>
      <dt class="col-sm-3">taskNote</dt><dd class="col-sm-9">{{ $task->taskNote }}</dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('task.edit',$task) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('task.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
