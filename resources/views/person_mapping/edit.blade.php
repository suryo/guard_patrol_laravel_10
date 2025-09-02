@extends('layouts.app')
@section('content')
<h5>Edit Person Mapping</h5>
<form method="post" action="{{ route('person-mapping.update', $person_mapping) }}">
  @method('PUT')
  @include('person_mapping._form', ['person_mapping'=>$person_mapping])
</form>
@endsection
