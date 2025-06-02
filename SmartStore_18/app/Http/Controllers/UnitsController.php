<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('settings.units', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'base_unit' => 'nullable|exists:units,id',
            'operator' => 'nullable|in:*,/,+,-',
            'operation_value' => 'nullable|numeric',
        ]);

        Unit::create($request->all());

        return redirect()->back()->with('success', 'Unit created successfully');
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'base_unit' => 'nullable|exists:units,id',
            'operator' => 'nullable|in:*,/,+,-',
            'operation_value' => 'nullable|numeric',
        ]);

        $unit->update($request->all());
        $unit->status = $request->has('status');
        $unit->save();

        return redirect()->back()->with('success', 'Unit updated successfully');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return response()->json(['success' => true]);
    }
}
