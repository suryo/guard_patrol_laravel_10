@extends('layouts.app')
@section('content')
<h5>Edit Task Template</h5>
<form method="post" action="{{ route('task-template.update', $task_template) }}">
  @method('PUT')
  @include('task_template._form', ['task_template'=>$task_template])
</form>
@endsection
