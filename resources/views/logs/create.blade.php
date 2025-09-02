@extends('layouts.app')
@section('content')
<h5>Tambah Log</h5>
<form method="post" action="{{ route('logs.store') }}">
  @include('logs._form')
</form>
@endsection
