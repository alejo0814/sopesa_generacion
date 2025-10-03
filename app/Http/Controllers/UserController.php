<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function editRoles($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit_roles', compact('user', 'roles'));
    }

    public function updateRoles(Request $request, $id)
    {
       //$user = User::findOrFail($id);
       //$roles = $request->roles ?? [];
       //$user->syncRoles($roles);
       //return redirect()->route('users.index')->with('success', 'Roles actualizados correctamente');

        $user = User::findOrFail($id);
        $roles = $request->roles ?? [];
        $roles = Role::whereIn('id', $roles)->get();
        $user->syncRoles($roles);
        return redirect()->route('users.index')->with('success', 'Roles actualizados correctamente');


    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
