@extends('layouts.app')
@section('content')
<h5>Tambah Task Template</h5>
<form method="post" action="{{ route('task-template.store') }}">
  @include('task_template._form')
</form>
@endsection
