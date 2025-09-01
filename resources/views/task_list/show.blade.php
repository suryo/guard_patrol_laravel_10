@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Detail Task</h2>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $task->nama_task }}</h4>
            <p class="card-text"><strong>Deskripsi:</strong><br> {{ $task->deskripsi ?? '-' }}</p>

            <p class="card-text">
                <strong>Status:</strong>
                @if($task->status == 'done')
                    <span class="badge bg-success">Done</span>
                @else
                    <span class="badge bg-warning">Pending</span>
                @endif
            </p>

            <p class="card-text">
                <strong>Deadline:</strong> {{ $task->deadline ?? '-' }}
            </p>

            <p class="card-text">
                <strong>Dibuat pada:</strong> {{ $task->created_at->format('d M Y H:i') }} <br>
                <strong>Diperbarui pada:</strong> {{ $task->updated_at->format('d M Y H:i') }}
            </p>
        </div>
        <div class="card-footer">
            <a href="{{ route('tb_task_list.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('tb_task_list.edit', $task->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('tb_task_list.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Yakin hapus task ini?')" class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection
