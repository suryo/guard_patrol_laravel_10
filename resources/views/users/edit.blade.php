@extends('layouts.app')
@section('content')
<h5>Edit User</h5>
<form method="post" action="{{ route('users.update', $user) }}">
  @method('PUT')
  @include('users._form', ['user'=>$user])
</form>
@endsection
