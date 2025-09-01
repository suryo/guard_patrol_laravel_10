@extends('layouts.app')
@section('content')
<h5>Tambah Report</h5>
<form method="post" action="{{ route('report.store') }}">
  @include('report._form')
</form>
@endsection
