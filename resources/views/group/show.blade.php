@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Detail Group</h5>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-primary btn-sm" href="{{ route('group.edit', $group->uid) }}">Edit</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('group.index') }}">Kembali</a>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">Group ID</dt>
      <dd class="col-sm-9">{{ $group->groupId }}</dd>

      <dt class="col-sm-3">Group Name</dt>
      <dd class="col-sm-9">{{ $group->groupName }}</dd>

      <dt class="col-sm-3">Time</dt>
      <dd class="col-sm-9">
        @php
          $ts = $group->timeStart ? \Illuminate\Support\Str::of($group->timeStart)->limit(5,'') : null;
          $te = $group->timeEnd ? \Illuminate\Support\Str::of($group->timeEnd)->limit(5,'') : null;
        @endphp
        {{ $ts ? $ts : '-' }} - {{ $te ? $te : '-' }}
      </dd>

      <dt class="col-sm-3">Note</dt>
      <dd class="col-sm-9">{{ $group->note ?: '-' }}</dd>

      <dt class="col-sm-3">Last Updated</dt>
      <dd class="col-sm-9">{{ $group->lastUpdated }}</dd>
    </dl>
  </div>
</div>
@endsection
