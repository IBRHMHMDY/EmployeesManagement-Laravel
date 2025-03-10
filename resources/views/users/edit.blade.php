@extends('layouts.app')

@section('content')
<div class="container">
    <h2>تعديل بيانات المستخدم</h2>
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>الاسم</label>
            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>الدور</label>
            <select name="role" class="form-control">
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>مستخدم</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>مسؤول</option>
            </select>
        </div>
        <button type="submit" class="btn btn-warning">تحديث</button>
    </form>
</div>
@endsection
