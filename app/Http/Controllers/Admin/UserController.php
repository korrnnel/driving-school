<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $role = in_array($request->query('role'), ['admin', 'instructor', 'student'], true)
            ? $request->query('role')
            : null;

        $query = User::with('assignedVehicle');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'role'));
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

    public function destroy(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Nie możesz usunąć własnego konta!');
        }

        if ($user->assigned_vehicle_id) {
            $user->update(['assigned_vehicle_id' => null]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Użytkownik został pomyślnie usunięty, a jego ewentualny pojazd wrócił do puli wolnych aut.');

    }
}
