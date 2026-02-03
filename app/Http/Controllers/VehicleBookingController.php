<?php

namespace App\Http\Controllers;

use App\Models\VehicleBooking;
use App\Models\Vehicle;
use App\Models\Personnel;
use App\Models\VehicleDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = VehicleBooking::with(['vehicle', 'user', 'driver'])->latest()->paginate(10);
        return view('vehicle-bookings.index', compact('bookings'));
    }

    public function create()
    {
        $vehicles = Vehicle::where('status', '!=', 'maintenance')->get();
        $drivers = VehicleDriver::active()->get();
        return view('vehicle-bookings.create', compact('vehicles', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'vehicle_driver_id' => 'nullable|exists:vehicle_drivers,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        // Check for overlaps (Simple check)
        $overlap = VehicleBooking::where('vehicle_id', $request->vehicle_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['vehicle_id' => 'ยานพาหนะนี้ถูกจองแล้วในช่วงเวลาดังกล่าว'])->withInput();
        }

        VehicleBooking::create($validated);

        return redirect()->route('vehicle-bookings.index')
            ->with('success', 'จองยานพาหนะเรียบร้อยแล้ว รอการอนุมัติ');
    }

    public function show(VehicleBooking $vehicleBooking)
    {
        return view('vehicle-bookings.show', compact('vehicleBooking'));
    }

    public function edit(VehicleBooking $vehicleBooking)
    {
        $vehicles = Vehicle::all();
        $drivers = VehicleDriver::active()->get();
        return view('vehicle-bookings.edit', compact('vehicleBooking', 'vehicles', 'drivers'));
    }

    public function update(Request $request, VehicleBooking $vehicleBooking)
    {
        // Check if updating status or full booking
        if ($request->has('status_update')) {
            $validated = $request->validate([
                'status' => 'required|in:pending,approved,completed,cancelled',
                'start_mileage' => 'nullable|integer',
                'end_mileage' => 'nullable|integer',
                'fuel_cost' => 'nullable|numeric',
            ]);
            
            $vehicleBooking->update($validated);
             // Update vehicle status if needed
            if ($validated['status'] == 'approved') {
                 $vehicleBooking->vehicle->update(['status' => 'in_use']);
            } elseif ($validated['status'] == 'completed' || $validated['status'] == 'cancelled') {
                 $vehicleBooking->vehicle->update(['status' => 'available']);
            }

            return back()->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
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

    public function destroy(VehicleBooking $vehicleBooking)
    {
        $vehicleBooking->delete();
        return redirect()->route('vehicle-bookings.index')
            ->with('success', 'ลบรายการจองเรียบร้อยแล้ว');
    }
}
