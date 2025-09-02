@extends('layouts.app')
@section('content')
<h5>Tambah Person Mapping</h5>
<form method="post" action="{{ route('person-mapping.store') }}">
  @include('person_mapping._form')
</form>
@endsection
