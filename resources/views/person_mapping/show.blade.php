@extends('layouts.app')
@section('content')
<h5>Detail Person Mapping</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $person_mapping->uid }}</dd>
      <dt class="col-sm-3">mappingId</dt><dd class="col-sm-9">{{ $person_mapping->mappingId }}</dd>
      <dt class="col-sm-3">mappingName</dt><dd class="col-sm-9">{{ $person_mapping->mappingName }}</dd>
      <dt class="col-sm-3">mappingTag</dt><dd class="col-sm-9">{{ $person_mapping->mappingTag }}</dd>
      <dt class="col-sm-3">userName</dt><dd class="col-sm-9">{{ $person_mapping->userName }}</dd>
      <dt class="col-sm-3">lastUpdated</dt><dd class="col-sm-9">{{ $person_mapping->lastUpdated }}</dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('person-mapping.edit',$person_mapping) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('person-mapping.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
