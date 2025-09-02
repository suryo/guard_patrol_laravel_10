@extends('layouts.app')
@section('content')
<h5>Edit Log</h5>
<form method="post" action="{{ route('logs.update', $log) }}">
  @method('PUT')
  @include('logs._form', ['log'=>$log])
</form>
@endsection
