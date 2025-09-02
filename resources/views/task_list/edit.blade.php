@extends('layouts.app')
@section('content')
<h5>Edit Task List</h5>
<form method="post" action="{{ route('task-list.update', $task_list) }}">
  @method('PUT')
  @include('task_list._form', ['task_list'=>$task_list])
</form>
@endsection
