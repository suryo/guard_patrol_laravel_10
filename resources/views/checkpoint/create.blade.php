@extends('layouts.app')
@section('content')
<h5>Tambah Checkpoint</h5>
<form method="post" action="{{ route('checkpoint.store') }}">
  @include('checkpoint._form')
</form>
@endsection
