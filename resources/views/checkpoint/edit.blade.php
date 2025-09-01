@extends('layouts.app')
@section('content')
<h5>Edit Checkpoint</h5>
<form method="post" action="{{ route('checkpoint.update', $checkpoint) }}">
  @method('PUT')
  @include('checkpoint._form', ['checkpoint'=>$checkpoint])
</form>
@endsection
