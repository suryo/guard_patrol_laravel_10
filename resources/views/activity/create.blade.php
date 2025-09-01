@extends('layouts.app')
@section('content')
<h5>Tambah Activity</h5>
<form method="post" action="{{ route('activity.store') }}">
  @include('activity._form')
</form>
@endsection
