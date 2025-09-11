@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Edit Group</h5>
  <a class="btn btn-outline-secondary" href="{{ route('group.index') }}">Kembali</a>
</div>

<form method="POST" action="{{ route('group.update', $group->uid) }}">
  @method('PUT')
  @include('group._form', ['submitText' => 'Update'])
</form>
@endsection
