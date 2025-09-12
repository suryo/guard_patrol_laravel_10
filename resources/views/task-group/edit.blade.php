@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Edit Task Group</h5>
  <a class="btn btn-outline-secondary" href="{{ route('task-group.index') }}">Kembali</a>
</div>

@include('components.alert')

<form method="post" action="{{ route('task-group.update', $task_group->uid) }}">
  @method('PUT')
  @include('task-group._form', ['task_group' => $task_group])
</form>
@endsection
