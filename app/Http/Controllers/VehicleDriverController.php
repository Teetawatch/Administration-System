<?php

namespace App\Http\Controllers;

use App\Models\VehicleDriver;
use Illuminate\Http\Request;

class VehicleDriverController extends Controller
{
    public function index()
    {
        $drivers = VehicleDriver::latest()->paginate(10);
        return view('vehicle-drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('vehicle-drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        VehicleDriver::create($validated);

        return redirect()->route('vehicle-drivers.index')
            ->with('success', 'เพิ่มพนักงานขับรถเรียบร้อยแล้ว');
    }

    public function edit(VehicleDriver $vehicleDriver)
    {
        return view('vehicle-drivers.edit', compact('vehicleDriver'));
    }

    public function update(Request $request, VehicleDriver $vehicleDriver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $vehicleDriver->update($validated);

        return redirect()->route('vehicle-drivers.index')
            ->with('success', 'ปรับปรุงข้อมูลพนักงานขับรถเรียบร้อยแล้ว');
    }

    public function destroy(VehicleDriver $vehicleDriver)
    {
        $vehicleDriver->delete();
        return redirect()->route('vehicle-drivers.index')
            ->with('success', 'ลบข้อมูลพนักงานขับรถเรียบร้อยแล้ว');
    }
}
