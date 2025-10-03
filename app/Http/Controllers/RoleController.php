<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $role = Role::create($request->only('name'));
        $role->syncPermissions($request->input('permissions'));
        return redirect()->route('roles.index');
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
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $role->update($request->only('name'));
        $role->syncPermissions($request->input('permissions'));
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index');
    }





    /*     public function editPermissions(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.editPermissions', compact('role', 'permissions'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $role->syncPermissions($request->input('permissions'));
        return redirect()->route('roles.index');
    } */


    public function editPermissions($id)
    {
        $role = Role::findById($id);
        $permissions = Permission::all();
        return view('roles.editPermissions', compact('role', 'permissions'));
    }

    public function updatePermissions(Request $request, $id)
    {
        // $role = Role::findById($id);
        // $role->syncPermissions($request->permissions);
        // return redirect()->route('roles.index')->with('success', 'Permisos actualizados correctamente');


        /* $role = Role::findById($id);
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);
        return redirect()->route('roles.index')->with('success', 'Permisos actualizados correctamente');



        $role = Role::findById($id);
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);
        return redirect()->route('roles.index')->with('success', 'Permisos actualizados correctamente');

        $role = Role::findById($id);
        $permissions = $request->permissions ?? [];
        $role->syncPermissions($permissions);
        return redirect()->route('roles.index')->with('success', 'Permisos actualizados correctamente'); */

        $role = Role::findById($id);
        $permissions = $request->permissions ?? [];
        $permissions = Permission::whereIn('id', $permissions)->get();
        $role->syncPermissions($permissions);
        return redirect()->route('roles.index')->with('success', 'Permisos actualizados correctamente');
    }
}
