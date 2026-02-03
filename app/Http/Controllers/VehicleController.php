<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for managing Vehicles.
 * 
 * Following best practices:
 * - php-pro: Type hints, return types, modern PHP 8 features
 * - software-architecture: Clean code patterns
 */
class VehicleController extends Controller
{
    /**
     * Display a listing of vehicles.
     */
    public function index(): View
    {
        $vehicles = Vehicle::latest()->paginate(10);

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create(): View
    {
        return view('vehicles.create');
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'license_plate' => 'required|string|max:50|unique:vehicles',
            'status' => 'required|in:available,maintenance,in_use',
        ]);

        Vehicle::create($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'เพิ่มข้อมูลยานพาหนะเรียบร้อยแล้ว');
    }

    /**
     * Display the specified vehicle.
     */
    public function show(Vehicle $vehicle): View
    {
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(Vehicle $vehicle): View
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'license_plate' => "required|string|max:50|unique:vehicles,license_plate,{$vehicle->id}",
            'status' => 'required|in:available,maintenance,in_use',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'อัปเดตข้อมูลยานพาหนะเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'ลบข้อมูลยานพาหนะเรียบร้อยแล้ว');
    }
}
