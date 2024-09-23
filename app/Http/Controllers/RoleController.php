<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('id', 'DESC')->whereNull('deleted_at')->get();
        return view('roles.index', [
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $permission = Permission::get();
        return view('roles.create',[
            'permission' => $permission
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        try {

            // Create new role
            $role = new Role();
            $role->name = $request->input('name');
            $role->created_at = Carbon::now();
            $role->created_by = Auth::user()->id;
            $role->save();

            // Convert permission input to array of integers and sync them
            $permissionsID = array_map(function ($value) {
                return (int)$value;
            }, $request->input('permission'));

            // Sync the permissions with the role
            $role->syncPermissions($permissionsID);

            return redirect()->route('roles.index')->with('message','Role has been successfully created.');

        } catch(\Exception $ex){

            return redirect()->back()->with('error','Something Went Wrong  - '.$ex->getMessage());
        }
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
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('roles.edit', [
            'role' => $role,
            'permission' => $permission,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();

        try {

            $role = Role::findOrFail($id);

            $role->name = $data['name'];
            $role->updated_at = Carbon::now();
            $role->updated_by = Auth::user()->id;
            $role->save();

            // Convert permission input to array of integers and sync them
            $permissionsID = array_map(function ($value) {
                return (int)$value;
            }, $data['permission']);

            // Sync permissions with the role
            $role->syncPermissions($permissionsID);

            return redirect()->route('roles.index')->with('message', 'Role updated successfully.');

        } catch (\Exception $ex) {
            // Handle any exception during the update process
            return redirect()->back()->with('error', 'Something went wrong - ' . $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data['deleted_by'] =  Auth::user()->id;
        $data['deleted_at'] =  Carbon::now();
        try {

            $role = Role::findOrFail($id);
            $role->update($data);

            return redirect()->route('roles.index')->with('message','Role has been successfully deleted.');
        } catch(\Exception $ex){

            return redirect()->back()->with('error','Something Went Wrong - '.$ex->getMessage());
        }
    }
}
