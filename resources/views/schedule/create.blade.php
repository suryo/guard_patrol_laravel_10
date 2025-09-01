@extends('layouts.app')
@section('content')
<h5>Tambah Schedule</h5>
<form method="post" action="{{ route('schedule.store') }}">
  @include('schedule._form')
</form>
@endsection
