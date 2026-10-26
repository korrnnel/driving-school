<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('assignedVehicle')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $availableVehicles = Vehicle::whereDoesntHave('instructor')->get();
        return view('admin.users.create', compact('availableVehicles'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        if ($data['role'] !== 'instructor') {
            $data['license_number'] = null;
            $data['assigned_vehicle_id'] = null;
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Użytkownik został pomyślnie dodany.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $availableVehicles = Vehicle::whereDoesntHave('instructor')
            ->orWhere('id', $user->assigned_vehicle_id)
            ->get();

        return view('admin.users.edit', compact('user', 'availableVehicles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($data['role'] !== 'instructor') {
            $data['license_number'] = null;
            $data['assigned_vehicle_id'] = null;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Dane użytkownika zostały pomyślnie zaktualizowane.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Nie możesz usunąć własnego konta!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Użytkownik został pomyślnie usunięty.');
    }
}
