<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\RoleResourceCollection;
use App\Http\Resources\RoleResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleController extends Controller
{
    //
    public function index()
    {
        $roles = Role::all();
        return new RoleResourceCollection($roles);
    }

    # ToDo: @Melusi remove used code
    public function create()
    {
         return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => ['required', ],'guard_name'=>'required']);
        $role = Role::create($validated);

        return response()->json([
            'status' => true,
            'message' => "Role Created successfully!",
            'Role' => new RoleResource($role)
        ], 200);
    }

    # ToDo: @Melusi remove used code
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate(['name' => ['required', 'min:3'],'guard_name'=>'required']);

        $role = $role->update($validated);

         return response()->json([
            'status' => true,
            'message' => "Role Updated successfully!",

        ], 200);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return back()->with('message', 'Role deleted.');
    }

    public function givePermission(Request $request, Role $role)
    {
        if($role->hasPermissionTo($request->permission)){
            return response()->json([
                'status' => false,
                'message' => "Permission Already exists",

            ], 200);
        }

        $role->givePermissionTo($request->permission);
        return response()->json([
            'status' => true,
            'message' => "Permission added successfully!",

        ], 200);
    }

    public function revokePermission(Role $role, Permission $permission)
    {
        if($role->hasPermissionTo($permission))
        {
            $role->revokePermissionTo($permission);
            return response()->json([
                'status' => true,
                'message' => "Permission Revoked",
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => "Permission Does NoT exists",

        ], 200);
    }


}


