@extends('layouts.app')
@section('content')
<h5>Tambah Task List</h5>
<form method="post" action="{{ route('task-list.store') }}">
  @include('task_list._form')
</form>
@endsection
