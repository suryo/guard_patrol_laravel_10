@extends('layouts.app')
@section('content')
<h5>Tambah User</h5>
<form method="post" action="{{ route('users.store') }}">
  @include('users._form')
</form>
@endsection
