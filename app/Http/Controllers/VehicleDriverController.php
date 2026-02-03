<?php

namespace App\Http\Controllers;

use App\Models\VehicleDriver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for managing Vehicle Drivers.
 * 
 * Following best practices:
 * - php-pro: Type hints, return types, modern PHP 8 features
 * - software-architecture: Clean code patterns
 */
class VehicleDriverController extends Controller
{
    /**
     * Display a listing of vehicle drivers.
     */
    public function index(): View
    {
        $drivers = VehicleDriver::latest()->paginate(10);

        return view('vehicle-drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new driver.
     */
    public function create(): View
    {
        return view('vehicle-drivers.create');
    }

    /**
     * Store a newly created driver in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        VehicleDriver::create($validated);

        return redirect()->route('vehicle-drivers.index')
            ->with('success', 'เพิ่มพนักงานขับรถเรียบร้อยแล้ว');
    }

    /**
     * Show the form for editing the specified driver.
     */
    public function edit(VehicleDriver $vehicleDriver): View
    {
        return view('vehicle-drivers.edit', compact('vehicleDriver'));
    }

    /**
     * Update the specified driver in storage.
     */
    public function update(Request $request, VehicleDriver $vehicleDriver): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $vehicleDriver->update($validated);

        return redirect()->route('vehicle-drivers.index')
            ->with('success', 'ปรับปรุงข้อมูลพนักงานขับรถเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified driver from storage.
     */
    public function destroy(VehicleDriver $vehicleDriver): RedirectResponse
    {
        $vehicleDriver->delete();

        return redirect()->route('vehicle-drivers.index')
            ->with('success', 'ลบข้อมูลพนักงานขับรถเรียบร้อยแล้ว');
    }
}
