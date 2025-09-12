@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Detail Task Group</h5>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-secondary" href="{{ route('task-group.index') }}">Kembali</a>
    <a class="btn btn-primary" href="{{ route('task-group.edit', $task_group->uid) }}">Edit</a>
  </div>
</div>

@include('components.alert')

<div class="card mb-3">
  <div class="card-body">
    <div class="row mb-2">
      <div class="col-md-3 text-muted">Group ID</div>
      <div class="col-md-9">{{ $task_group->groupId }}</div>
    </div>
    <div class="row mb-2">
      <div class="col-md-3 text-muted">Group Name</div>
      <div class="col-md-9 fw-semibold">{{ $task_group->groupName }}</div>
    </div>
    <div class="row mb-2">
      <div class="col-md-3 text-muted">User</div>
      <div class="col-md-9">{{ $task_group->userName ?? '-' }}</div>
    </div>
    <div class="row mb-2">
      <div class="col-md-3 text-muted">Updated</div>
      <div class="col-md-9">{{ \Illuminate\Support\Carbon::parse($task_group->lastUpdated)->format('Y-m-d H:i:s') }}</div>
    </div>
    <div class="row">
      <div class="col-md-3 text-muted">Note</div>
      <div class="col-md-9">{!! nl2br(e($task_group->groupNote)) !!}</div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div class="fw-semibold">Tasks di dalam Group</div>
    <span class="badge text-bg-secondary">{{ $task_group->details_count ?? $task_group->details()->count() }} items</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-sm mb-0 align-middle">
        <thead>
          <tr>
            <th style="width:90px">UID</th>
            <th>Task ID</th>
            <th>Task Name</th>
            <th style="width:110px">Sort</th>
          </tr>
        </thead>
        <tbody>
          @forelse(($task_group->details ?? []) as $d)
            <tr>
              <td class="text-muted">{{ $d->task->uid ?? $d->task_uid }}</td>
              <td>{{ $d->task->taskId ?? '-' }}</td>
              <td>{{ $d->task->taskName ?? '-' }}</td>
              <td>{{ $d->sortOrder }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="text-center text-muted">Belum ada task dalam group ini.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
