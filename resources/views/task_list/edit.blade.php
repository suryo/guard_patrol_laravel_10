@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Edit Task</h2>
    <form action="{{ route('tb_task_list.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama Task</label>
            <input type="text" name="nama_task" class="form-control" value="{{ $task->nama_task }}" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ $task->deskripsi }}</textarea>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="pending" {{ $task->status=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="done" {{ $task->status=='done' ? 'selected' : '' }}>Done</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Deadline</label>
            <input type="date" name="deadline" class="form-control" value="{{ $task->deadline }}">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('tb_task_list.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
