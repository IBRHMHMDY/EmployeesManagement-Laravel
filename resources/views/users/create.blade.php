@extends('layouts.app')

@section('content')
<div class="container">
    <h2>إضافة مستخدم جديد</h2>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>الاسم</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>كلمة المرور</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>الدور</label>
            <select name="role" class="form-control">
                <option value="user">مستخدم</option>
                <option value="admin">مسؤول</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">حفظ</button>
    </form>
</div>
@endsection
