@extends('layouts.app')
@section('content')
<h5>Tambah Schedule Template</h5>
<form method="post" action="{{ route('schedule-template.store') }}">
  @include('schedule_template._form')
</form>
@endsection
