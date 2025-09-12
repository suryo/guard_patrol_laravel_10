@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Tambah Task Group</h5>
  <a class="btn btn-outline-secondary" href="{{ route('task-group.index') }}">Kembali</a>
</div>

@include('components.alert')

<form method="post" action="{{ route('task-group.store') }}">
  @include('task-group._form')
</form>
@endsection
