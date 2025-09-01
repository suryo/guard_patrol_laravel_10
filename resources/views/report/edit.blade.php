@extends('layouts.app')
@section('content')
<h5>Edit Report</h5>
<form method="post" action="{{ route('report.update', $report) }}">
  @method('PUT')
  @include('report._form', ['report'=>$report])
</form>
@endsection
