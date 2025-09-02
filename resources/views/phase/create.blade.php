@extends('layouts.app')
@section('content')
<h5>Tambah Phase</h5>
<form method="post" action="{{ route('phase.store') }}">
  @include('phase._form')
</form>
@endsection
