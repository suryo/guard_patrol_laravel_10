@extends('layouts.app')
@section('content')
<h5>Detail Schedule</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $schedule->uid }}</dd>
      <dt class="col-sm-3">scheduleId</dt><dd class="col-sm-9">{{ $schedule->scheduleId }}</dd>
      <dt class="col-sm-3">personId</dt><dd class="col-sm-9">{{ $schedule->personId }}</dd>
      <dt class="col-sm-3">mappingId</dt><dd class="col-sm-9">{{ $schedule->mappingId }}</dd>
      <dt class="col-sm-3">activityId</dt><dd class="col-sm-9">{{ $schedule->activityId }}</dd>
      <dt class="col-sm-3">checkpointName</dt><dd class="col-sm-9">{{ $schedule->checkpointName }}</dd>
      <dt class="col-sm-3">scheduleName</dt><dd class="col-sm-9">{{ $schedule->scheduleName }}</dd>
      <dt class="col-sm-3">scheduleDate</dt><dd class="col-sm-9">{{ $schedule->scheduleDate }}</dd>
      <dt class="col-sm-3">scheduleStart</dt><dd class="col-sm-9">{{ $schedule->scheduleStart }}</dd>
      <dt class="col-sm-3">scheduleEnd</dt><dd class="col-sm-9">{{ $schedule->scheduleEnd }}</dd>
      <dt class="col-sm-3">userName</dt><dd class="col-sm-9">{{ $schedule->userName }}</dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('schedule.edit',$schedule) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
