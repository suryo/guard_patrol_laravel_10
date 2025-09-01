@extends('layouts.app')
@section('content')
<h5>Detail Activity</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $activity->uid }}</dd>
      <dt class="col-sm-3">activityId</dt><dd class="col-sm-9">{{ $activity->activityId }}</dd>
      <dt class="col-sm-3">personId</dt><dd class="col-sm-9">{{ $activity->personId }}</dd>
      <dt class="col-sm-3">scheduleId</dt><dd class="col-sm-9">{{ $activity->scheduleId }}</dd>
      <dt class="col-sm-3">checkpointStart</dt><dd class="col-sm-9">{{ $activity->checkpointStart }}</dd>
      <dt class="col-sm-3">checkpointEnd</dt><dd class="col-sm-9">{{ $activity->checkpointEnd }}</dd>
      <dt class="col-sm-3">activityStart</dt><dd class="col-sm-9">{{ $activity->activityStart }}</dd>
      <dt class="col-sm-3">activityEnd</dt><dd class="col-sm-9">{{ $activity->activityEnd }}</dd>
      <dt class="col-sm-3">activityStatus</dt>
      <dd class="col-sm-9">
        @php
          $badge = ['0'=>'secondary','1'=>'success','2'=>'danger'][$activity->activityStatus] ?? 'secondary';
          $label = ['0'=>'Planned','1'=>'Done','2'=>'Miss'][$activity->activityStatus] ?? $activity->activityStatus;
        @endphp
        <span class="badge text-bg-{{ $badge }}">{{ $label }}</span>
      </dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('activity.edit',$activity) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('activity.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
