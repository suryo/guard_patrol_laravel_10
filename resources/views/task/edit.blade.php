@extends('layouts.app')
@section('content')
<h5>Edit Task</h5>
<form method="post" action="{{ route('task.update', $task) }}">
  @method('PUT')
  @include('task._form', ['task'=>$task])
</form>
@endsection
