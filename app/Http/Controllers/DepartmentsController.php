<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentsController extends Controller
{
    public function index(Request $request) {
        $query = Department::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $departments = $query->paginate(10);
        return view('departments.index', compact('departments'));

    }

    public function create() {
        return view('departments.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:departments,name',
            'description' => 'nullable|string'
        ]);

        Department::create($request->all());

        return redirect()->route('departments.index')->with('success', 'تم إضافة القسم بنجاح');
    }

    public function edit(Department $department) {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department) {
        $request->validate([
            'name' => 'required|unique:departments,name,' . $department->id,
            'description' => 'nullable|string'
        ]);

        $department->update($request->all());

        return redirect()->route('departments.index')->with('success', 'تم تحديث القسم بنجاح');
    }

    public function destroy(Department $department) {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'تم حذف القسم بنجاح');
    }
}
