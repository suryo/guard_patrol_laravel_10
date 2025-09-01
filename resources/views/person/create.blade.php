@extends('layouts.app')
@section('content')
<h5>Tambah Person</h5>
<form method="post" action="{{ route('person.store') }}">
  @include('person._form')
</form>
@endsection
