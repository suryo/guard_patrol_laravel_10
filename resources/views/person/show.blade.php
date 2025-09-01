@extends('layouts.app')
@section('content')
<h5>Detail Person</h5>
<ul class="list-group">
  <li class="list-group-item">UID: {{ $person->uid }}</li>
  <li class="list-group-item">ID: {{ $person->personId }}</li>
  <li class="list-group-item">Name: {{ $person->personName }}</li>
  <li class="list-group-item">User: {{ $person->userName }}</li>
  <li class="list-group-item">Deleted: {{ $person->isDeleted }}</li>
</ul>
<a href="{{ route('person.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
