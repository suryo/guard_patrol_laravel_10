@extends('layouts.app')
@section('content')
<h5>Laporan Patrol</h5>
<form class="row g-2 mb-2">
  <div class="col-md-3"><input name="personName" class="form-control" placeholder="Person" value="{{ request('personName') }}"></div>
  <div class="col-md-3"><input type="date" name="reportDate" class="form-control" value="{{ request('reportDate') }}"></div>
  <div class="col-md-3"><input type="date" name="scheduleDate" class="form-control" value="{{ request('scheduleDate') }}"></div>
  <div class="col-md-3"><button class="btn btn-primary w-100">Filter</button></div>
</form>

<table class="table table-sm table-bordered">
  <thead class="table-light">
    <tr>
      <th>ReportDate</th><th>Person</th><th>Schedule</th><th>Checkpoint</th>
      <th>Start</th><th>End</th>
    </tr>
  </thead>
  <tbody>
    @foreach($items as $i)
    <tr>
      <td>{{ $i->reportDate }}</td>
      <td>{{ $i->personName }}</td>
      <td>{{ $i->scheduleId }}</td>
      <td>{{ $i->checkpointName }}</td>
      <td>{{ $i->activityStart }}</td>
      <td>{{ $i->activityEnd }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
{{ $items->links() }}
@endsection
