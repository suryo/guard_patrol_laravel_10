@extends('layouts.app')
@section('content')
<h5>Detail Report</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $report->uid }}</dd>
      <dt class="col-sm-3">reportId</dt><dd class="col-sm-9">{{ $report->reportId }}</dd>
      <dt class="col-sm-3">personId</dt><dd class="col-sm-9">{{ $report->personId }}</dd>
      <dt class="col-sm-3">activityId</dt><dd class="col-sm-9">{{ $report->activityId }}</dd>
      <dt class="col-sm-3">checkpointName</dt><dd class="col-sm-9">{{ $report->checkpointName }}</dd>
      <dt class="col-sm-3">reportDate</dt><dd class="col-sm-9">{{ $report->reportDate }}</dd>
      <dt class="col-sm-3">reportTime</dt><dd class="col-sm-9">{{ $report->reportTime }}</dd>
      <dt class="col-sm-3">Latitude</dt><dd class="col-sm-9">{{ $report->reportLatitude }}</dd>
      <dt class="col-sm-3">Longitude</dt><dd class="col-sm-9">{{ $report->reportLongitude }}</dd>
      <dt class="col-sm-3">reportNote</dt><dd class="col-sm-9">{{ $report->reportNote }}</dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('report.edit',$report) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('report.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
