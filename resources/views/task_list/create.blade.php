@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Tambah Task</h2>
    <form action="{{ route('tb_task_list.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Task</label>
            <input type="text" name="nama_task" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="pending">Pending</option>
                <option value="done">Done</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Deadline</label>
            <input type="date" name="deadline" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('tb_task_list.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
