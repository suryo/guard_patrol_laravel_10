@extends('layouts.app')
@section('content')
<h5>Edit Schedule Template</h5>
<form method="post" action="{{ route('schedule-template.update', $schedule_template) }}">
  @method('PUT')
  @include('schedule_template._form', ['schedule_template'=>$schedule_template])
</form>
@endsection
