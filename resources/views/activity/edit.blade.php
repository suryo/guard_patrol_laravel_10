@extends('layouts.app')
@section('content')
<h5>Edit Activity</h5>
<form method="post" action="{{ route('activity.update', $activity) }}">
  @method('PUT')
  @include('activity._form', ['activity'=>$activity])
</form>
@endsection
