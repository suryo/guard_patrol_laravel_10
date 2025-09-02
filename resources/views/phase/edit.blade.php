@extends('layouts.app')
@section('content')
<h5>Edit Phase</h5>
<form method="post" action="{{ route('phase.update', $phase) }}">
  @method('PUT')
  @include('phase._form', ['phase'=>$phase])
</form>
@endsection
