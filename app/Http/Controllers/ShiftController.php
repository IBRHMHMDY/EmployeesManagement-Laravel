<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = Shift::all();
        return view('shifts.index', compact('shifts')); // resources/views/shifts/index.blade.php
    }
    // عرض نموذج إنشاء وردية جديدة
    public function create()
    {
        return view('shifts.create');
    }

    // حفظ وردية جديدة
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => ['required', 'regex:/^(0[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/'],
            'end_time' => ['required', 'regex:/^(0[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/'],
        ], [
            'start_time.regex' => 'الوقت يجب أن يكون بصيغة صحيحة مثل 08:30 AM أو 05:45 PM',
            'end_time.regex' => 'الوقت يجب أن يكون بصيغة صحيحة مثل 08:30 AM أو 05:45 PM',
        ]);

        Shift::create($request->all());

        return redirect()->route('shifts.index')->with('success', 'تمت إضافة الوردية بنجاح');
    }

    // عرض نموذج تعديل الوردية
    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        return view('shifts.edit', compact('shift'));
    }

    // تحديث بيانات الوردية
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $shift = Shift::findOrFail($id);
        $shift->update($request->all());

        return redirect()->route('shifts.index')->with('success', 'تم تحديث الوردية بنجاح');
    }

    // حذف وردية
    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();

        return redirect()->route('shifts.index')->with('success', 'تم حذف الوردية بنجاح');
    }
}
