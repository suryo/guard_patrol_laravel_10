@extends('layouts.app')
@section('content')
<h5>Edit Schedule</h5>
<form method="post" action="{{ route('schedule.update', $schedule) }}">
  @method('PUT')
  @include('schedule._form', ['schedule'=>$schedule])
</form>
@endsection
