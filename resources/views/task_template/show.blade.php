@extends('layouts.app')
@section('content')
<h5>Detail Task Template</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $task_template->uid }}</dd>
      <dt class="col-sm-3">taskId</dt><dd class="col-sm-9">{{ $task_template->taskId }}</dd>
      <dt class="col-sm-3">taskName</dt><dd class="col-sm-9">{{ $task_template->taskName }}</dd>
      <dt class="col-sm-3">taskNote</dt><dd class="col-sm-9">{{ $task_template->taskNote }}</dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('task-template.edit',$task_template) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('task-template.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
