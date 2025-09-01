@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-2">
  <h5>Person</h5>
  <a class="btn btn-primary" href="{{ route('person.create') }}">Tambah</a>
</div>

<table class="table table-sm table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th><th>personId</th><th>personName</th><th>userName</th><th>isDeleted</th><th>aksi</th>
    </tr>
  </thead>
  <tbody>
    @foreach($items as $i)
    <tr>
      <td>{{ $i->uid }}</td>
      <td>{{ $i->personId }}</td>
      <td><a href="{{ route('person.show',$i) }}">{{ $i->personName }}</a></td>
      <td>{{ $i->userName }}</td>
      <td>{{ $i->isDeleted }}</td>
      <td class="text-nowrap">
        <a class="btn btn-sm btn-warning" href="{{ route('person.edit',$i) }}">Edit</a>
        <form class="d-inline" method="post" action="{{ route('person.destroy',$i) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Hapus</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
{{ $items->links() }}
@endsection
