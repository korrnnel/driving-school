<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
   public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $category = in_array($request->query('category'), ['A', 'B', 'C'], true)
            ? $request->query('category')
            : null;
        $status = in_array($request->query('status'), ['active', 'maintenance', 'out_of_service'], true)
            ? $request->query('status')
            : null;

        $query = Vehicle::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $vehicles = $query->latest()->paginate(10)->withQueryString();

        return view('admin.vehicles.index', compact('vehicles', 'search', 'category', 'status'));
    }

    public function create()
    {
        return view('admin.vehicles.create');
    }

    public function store(StoreVehicleRequest $request)
    {
        Vehicle::create($request->validated());

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Pojazd został pomyślnie dodany.');
    }

    public function show(Vehicle $vehicle)
    {
        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Pojazd został pomyślnie zaktualizowany.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Pojazd został pomyślnie usunięty.');
    }
}
