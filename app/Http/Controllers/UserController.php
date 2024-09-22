<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::orderBy("id","desc")->whereNull('deleted_at')->get();
        return view('users.index',[
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $roles = Role::pluck('name','name')->orderBy("id","desc")->whereNull('deleted_at')->get();
        return view('users.create',[
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated(); // Validate incoming request data
        try {
            // Create new user instance
            $user = new User();

            // Set user details
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']); // Hash the password

            // Add inserted fields
            $user->created_at = Carbon::now();
            $user->created_by = Auth::user()->id;

            // Save the user to the database
            $user->save();

            // Assign roles to the user
            $user->assignRole($data['roles']);

            return redirect()->route('users.index')->with('message', 'User has been successfully created.');

        } catch (\Exception $ex) {
            // Handle any exception during the creation process
            return redirect()->back()->with('error', 'Something went wrong - ' . $ex->getMessage());
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
        $user = User::findOrFail($id);
        $roles = Role::pluck('name','name')->orderBy("id","desc")->whereNull('deleted_at')->get();
        $userRole = $user->roles->pluck('name','name')->orderBy("id","desc")->whereNull('deleted_at')->get();
        return view('users.edit',[
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated(); // Validate incoming request data
        try {
            // Find the user by ID or throw an exception if not found
            $user = User::findOrFail($id);

            // Update user details
            $user->name = $data['name'];
            $user->email = $data['email'];

            // Update password only if provided
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }else{
                $input = Arr::except($user,array('password'));
            }

            // Add modified fields
            $user->updated_at = Carbon::now();
            $user->updated_by = Auth::user()->id;

            // Save the user
            $user->save();

            // Remove existing roles before assigning new ones
            DB::table('model_has_roles')->where('model_id', $id)->delete();

            // Assign new roles to the user
            $user->assignRole($data['roles']);

            return redirect()->route('users.index')->with('message', 'User updated successfully.');

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

            $user = User::findOrFail($id);
            $user->update($data);

            return redirect()->route('users.index')->with('message','User has been successfully deleted.');
        } catch(\Exception $ex){

            return redirect()->back()->with('error','Something Went Wrong - '.$ex->getMessage());
        }
    }
}
