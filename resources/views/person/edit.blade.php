@extends('layouts.app')
@section('content')
<h5>Edit Person</h5>
<form method="post" action="{{ route('person.update', $person) }}">
  @method('PUT')
  @include('person._form', ['person'=>$person])
</form>
@endsection
