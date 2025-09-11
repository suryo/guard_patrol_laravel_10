@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Tambah Group</h5>
  <a class="btn btn-outline-secondary" href="{{ route('group.index') }}">Kembali</a>
</div>

<form method="POST" action="{{ route('group.store') }}">
  @include('group._form', ['submitText' => 'Tambah'])
</form>
@endsection
