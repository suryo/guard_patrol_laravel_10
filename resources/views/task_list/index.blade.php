@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Task List</h2>
    <a href="{{ route('tb_task_list.create') }}" class="btn btn-primary mb-3">+ Tambah Task</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Task</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Deadline</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>{{ $task->nama_task }}</td>
                <td>{{ $task->deskripsi }}</td>
                <td>
                    @if($task->status == 'done')
                        <span class="badge bg-success">Done</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </td>
                <td>{{ $task->deadline }}</td>
                <td>
                    <a href="{{ route('tb_task_list.edit', $task->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('tb_task_list.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin hapus task ini?')" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">Belum ada task</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
