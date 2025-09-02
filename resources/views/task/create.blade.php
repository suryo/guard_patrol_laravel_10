@extends('layouts.app')
@section('content')
<h5>Tambah Task</h5>
<form method="post" action="{{ route('task.store') }}">
  @include('task._form')
</form>
@endsection
