@extends('layouts.app')
@section('content')
<h5>Detail Task List</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $task_list->uid }}</dd>
      <dt class="col-sm-3">taskId</dt><dd class="col-sm-9">{{ $task_list->taskId }}</dd>
      <dt class="col-sm-3">scheduleId</dt><dd class="col-sm-9">{{ $task_list->scheduleId }}</dd>
      <dt class="col-sm-3">phaseId</dt><dd class="col-sm-9">{{ $task_list->phaseId }}</dd>
      <dt class="col-sm-3">taskStatus</dt>
      <dd class="col-sm-9">
        @php
          $badge = $task_list->taskStatus==='1' ? 'success' : 'secondary';
          $label = $task_list->taskStatus==='1' ? 'Selesai' : 'Pending';
        @endphp
        <span class="badge text-bg-{{ $badge }}">{{ $label }}</span>
      </dd>
      <dt class="col-sm-3">userName</dt><dd class="col-sm-9">{{ $task_list->userName }}</dd>
      <dt class="col-sm-3">lastUpdated</dt><dd class="col-sm-9">{{ $task_list->lastUpdated }}</dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('task-list.edit',$task_list) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('task-list.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
