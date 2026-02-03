<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleBooking;
use App\Models\VehicleDriver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller for managing Vehicle Bookings.
 * 
 * Following best practices:
 * - php-pro: Type hints, return types, modern PHP 8 features
 * - software-architecture: Clean code patterns
 */
class VehicleBookingController extends Controller
{
    /**
     * Display a listing of vehicle bookings.
     */
    public function index(): View
    {
        $bookings = VehicleBooking::with(['vehicle', 'user', 'driver'])
            ->latest()
            ->paginate(10);

        return view('vehicle-bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(): View
    {
        $vehicles = Vehicle::bookable()->get();
        $drivers = VehicleDriver::active()->get();

        return view('vehicle-bookings.create', compact('vehicles', 'drivers'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'vehicle_driver_id' => 'nullable|exists:vehicle_drivers,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string',
        ]);

        // Check for overlapping bookings
        if ($this->hasOverlappingBooking($request->vehicle_id, $request->start_time, $request->end_time)) {
            return back()
                ->withErrors(['vehicle_id' => 'ยานพาหนะนี้ถูกจองแล้วในช่วงเวลาดังกล่าว'])
                ->withInput();
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = VehicleBooking::STATUS_PENDING;

        VehicleBooking::create($validated);

        return redirect()->route('vehicle-bookings.index')
            ->with('success', 'จองยานพาหนะเรียบร้อยแล้ว รอการอนุมัติ');
    }

    /**
     * Display the specified booking.
     */
    public function show(VehicleBooking $vehicleBooking): View
    {
        return view('vehicle-bookings.show', compact('vehicleBooking'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(VehicleBooking $vehicleBooking): View
    {
        $vehicles = Vehicle::all();
        $drivers = VehicleDriver::active()->get();

        return view('vehicle-bookings.edit', compact('vehicleBooking', 'vehicles', 'drivers'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, VehicleBooking $vehicleBooking): RedirectResponse
    {
        // Check if updating status only
        if ($request->has('status_update')) {
            return $this->updateStatus($request, $vehicleBooking);
        }

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'vehicle_driver_id' => 'nullable|exists:vehicle_drivers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string',
        ]);

        $vehicleBooking->update($validated);

        return redirect()->route('vehicle-bookings.index')
            ->with('success', 'แก้ไขข้อมูลการจองเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(VehicleBooking $vehicleBooking): RedirectResponse
    {
        $vehicleBooking->delete();

        return redirect()->route('vehicle-bookings.index')
            ->with('success', 'ลบรายการจองเรียบร้อยแล้ว');
    }

    /**
     * Update booking status and vehicle status.
     */
    private function updateStatus(Request $request, VehicleBooking $vehicleBooking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,completed,cancelled',
            'start_mileage' => 'nullable|integer',
            'end_mileage' => 'nullable|integer',
            'fuel_cost' => 'nullable|numeric',
        ]);

        $vehicleBooking->update($validated);

        // Update vehicle status based on booking status
        $this->updateVehicleStatus($vehicleBooking, $validated['status']);

        return back()->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
    }

    /**
     * Update vehicle status based on booking status.
     */
    private function updateVehicleStatus(VehicleBooking $booking, string $status): void
    {
        $vehicleStatus = match ($status) {
            VehicleBooking::STATUS_APPROVED => Vehicle::STATUS_IN_USE,
            VehicleBooking::STATUS_COMPLETED,
            VehicleBooking::STATUS_CANCELLED => Vehicle::STATUS_AVAILABLE,
            default => null,
        };

        if ($vehicleStatus) {
            $booking->vehicle->update(['status' => $vehicleStatus]);
        }
    }

    /**
     * Check if there's an overlapping booking.
     */
    private function hasOverlappingBooking(int $vehicleId, string $startTime, string $endTime, ?int $excludeId = null): bool
    {
        return VehicleBooking::overlapping($vehicleId, $startTime, $endTime, $excludeId)->exists();
    }
}
